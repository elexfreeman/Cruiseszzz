<?php
/**
 * Created by PhpStorm.
 * User: elex
 * Date: 15-Oct-15
 * Time: 3:04 AM
 */
//$having=" having ";

/*//


JOIN (
SELECT kr.id kr_weekend_id, kr.pagetitle kr_weekend_title, tv.name kr_weekend_tv_name, cv.value kr_weekend_tv_value
FROM modx_site_content kr
LEFT JOIN modx_site_tmplvar_contentvalues cv ON kr.id=cv.contentid
LEFT JOIN modx_site_tmplvars tv ON tv.id=cv.tmplvarid
WHERE (kr.parent IN (19430)) AND(kr.template=3) AND(tv.name='kr_weekend')) weekend
ON cities_start.kr_cities_start_id=kr_weekend_id
*/
$search_date_text="Любая";
if ((isset($_GET['city_start']))and($_GET['city_start']!=''))
{
	$having.="AND(kr_cities_start_tv_value like '".EscapeString($_GET['city_start'])."%')";
}

if ((isset($_GET['city']))and($_GET['city']!=''))
{
	$having.="AND(kr_city_tv_value like '%".EscapeString($_GET['city'])."%')";
}




if ((isset($_GET['weekend']))and($_GET['weekend']=='1'))
{
	$having.="AND(kr_weekend_tv_value > 0)";
}

/*created_at BETWEEN STR_TO_DATE('2008-08-14 00:00:00', '%Y-%m-%d %H:%i:%s')
  AND STR_TO_DATE('2008-08-23 23:59:59', '%Y-%m-%d %H:%i:%s');*/
if((isset($_GET['date_start']))and($_GET['date_start']!='')and(isset($_GET['date_stop']))and($_GET['date_stop']!=''))
{
	$having.="AND(kr_date_start_tv_value >= '".EscapeString($_GET['date_start'])."')";
	$having.="AND(kr_date_start_tv_value <= '".EscapeString($_GET['date_stop'])."')";

}else
{
	if ((isset($_GET['date_start']))and($_GET['date_start']!=''))
	{
		$having.="AND(kr_date_start_tv_value >= '".EscapeString($_GET['date_start'])."')";
	}


	if ((isset($_GET['date_stop']))and($_GET['date_stop']!=''))
	{
		$having.="AND(kr_date_start_tv_value <= '".EscapeString($_GET['date_stop'])."')";
	}
}



if($having!='')
{
	$having = " having ".substr($having, 3);
}

$sql_ship="
-- ********************************
						select ship_id id from
						(
						select
						ships.id ship_id,
						ships.pagetitle ship_title,
						tv.name tv_name,
						cv.value tv_value


						from ".$table_prefix."site_content ships

						left join ".$table_prefix."site_tmplvar_contentvalues cv
						on ships.id=cv.contentid

						left join ".$table_prefix."site_tmplvars tv
						on tv.id=cv.tmplvarid


						where (ships.parent=2)and(tv.name='t_in_filtr')

						) ships_tbl
					-- ********************************
";

if ((isset($_GET['ship']))and($_GET['ship']!=''))
{
	$sql_ship=EscapeString($_GET['ship']);
}

$sql="select * from
(
		select
			kr.id kr_cities_start_id,
			kr.pagetitle kr_cities_start_title,
			tv.name kr_cities_start_tv_name,
			cv.value kr_cities_start_tv_value


			from ".$table_prefix."site_content kr

			left join ".$table_prefix."site_tmplvar_contentvalues cv
			on kr.id=cv.contentid

			left join ".$table_prefix."site_tmplvars tv
			on tv.id=cv.tmplvarid

			where (kr.parent in
						(

					".$sql_ship."
					)
		)

		and(kr.template=".$this->CruisTemplate." )and(tv.name='kr_cities')
) 	cities_start



-- ====================

    join
    (
      select
        kr.id kr_weekend_id,
        kr.pagetitle kr_weekend_title,
        tv.name kr_weekend_tv_name,
        cv.value kr_weekend_tv_value


      from modx_site_content kr

        left join modx_site_tmplvar_contentvalues cv
          on kr.id=cv.contentid

        left join modx_site_tmplvars tv
          on tv.id=cv.tmplvarid

      where (kr.parent in
             (


            ".$sql_ship."

             )
            )

            and(kr.template=3 )and(tv.name='kr_weekend')
    ) 	weekend
      on cities_start.kr_cities_start_id=kr_weekend_id


-- ===================

join
(
		select
			kr.id kr_city_id,
			kr.pagetitle kr_city_title,
			tv.name kr_city_tv_name,
			cv.value kr_city_tv_value


			from ".$table_prefix."site_content kr

			left join ".$table_prefix."site_tmplvar_contentvalues cv
			on kr.id=cv.contentid

			left join ".$table_prefix."site_tmplvars tv
			on tv.id=cv.tmplvarid

			where (kr.parent in
						(

				".$sql_ship."
					)
		)

		and(kr.template=".$this->CruisTemplate." )and(tv.name='kr_cities')
) 	cities
on cities_start.kr_cities_start_id=kr_city_id

join
(
		select
			kr.id kr_date_start_id,
			kr.pagetitle kr_date_start_title,
			tv.name kr_date_start_tv_name,
			STR_TO_DATE(cv.value, '%d.%m.%Y') kr_date_start_tv_value


			from ".$table_prefix."site_content kr

			left join ".$table_prefix."site_tmplvar_contentvalues cv
			on kr.id=cv.contentid

			left join ".$table_prefix."site_tmplvars tv
			on tv.id=cv.tmplvarid

			where (kr.parent in
						(

					".$sql_ship."
					)
		)

		and(kr.template=".$this->CruisTemplate." )and(tv.name='kr_date_start')
) 	date_start
on cities_start.kr_cities_start_id=kr_date_start_id


join
(
		select
			kr.id kr_date_end_id,
			kr.pagetitle kr_date_end_title,
			tv.name kr_date_end_tv_name,
			STR_TO_DATE(cv.value, '%d.%m.%Y') kr_date_end_tv_value


			from ".$table_prefix."site_content kr

			left join ".$table_prefix."site_tmplvar_contentvalues cv
			on kr.id=cv.contentid

			left join ".$table_prefix."site_tmplvars tv
			on tv.id=cv.tmplvarid

			where (kr.parent in
						(

				".$sql_ship."
					)
		)

		and(kr.template=".$this->CruisTemplate." )and(tv.name='kr_date_end')
) 	date_end
on cities_start.kr_cities_start_id=date_end.kr_date_end_id
".$having."
order by date_start.kr_date_start_tv_value
limit 30
";
echo "<pre style='display:none' >";
echo $sql;
echo "</pre>";

$qq=$modx->query($sql);
foreach ($qq as $row)
{
    $cruis=GetPageInfo($row['kr_cities_start_id']);
	$ship=GetPageInfo($cruis->parent);
	$prices=$this->GetCruisPriceList($cruis->id);

	//Вычисляем паддинг вывода маршрута
	$route_padding='padding-top: 26px;';
	if(mb_strlen($cruis->TV['kr_route'],'UTF-8')>30)
	{
		$route_padding='padding-top: 13px;';
	}


	//Убераем * из маршрута
	$cruis->TV['kr_route']=str_replace('*','' ,$cruis->TV['kr_route']);

	//день/дней
	$days_text='дней';
	$cruis->TV['kr_days']= $cruis->TV['kr_days']+0;
	if( $cruis->TV['kr_days']==1) $days_text='день';
	if(in_array($cruis->TV['kr_days'],array(2,3,4)) ) $days_text='дня';

	//ночь/ночей
	$night_text='ночей';
	$cruis->TV['kr_nights']= $cruis->TV['kr_nights']+0;
	if( $cruis->TV['kr_nights']==1) $night_text='ночь';
	if(in_array($cruis->TV['kr_nights'],array(2,3,4)) ) $night_text='ночи';



	?>



<!-- NEW 2 --->
	<div class="w-clearfix resultitem">
		<div class="sitemimg"  style='background-image: linear-gradient(rgba(0, 92, 255, 0.52) 5%, rgba(255, 255, 255, 0)), url("<?php echo $ship->TV['t_title_img']; ?>");'>Круиз на теплоходе<br>"
			<?php echo $ship->TV['t_title']; ?>"</div>
		<div class="sicenter">
			<div class="sicentertop">
				<div class="sicentertexttop"><?php echo $cruis->TV['kr_route']; ?></div>
			</div>
			<div class="siteminfoblocktext"><?php echo $cruis->TV['kr_date_start']; ?> - <?php echo $cruis->TV['kr_date_end']; ?><br><span
					class="sitemdn"><?php echo $cruis->TV['kr_days']; ?> <?php echo $days_text; ?> / <?php echo $cruis->TV['kr_nights']; ?> <?php echo $night_text; ?></span><br>
				<span class="sitemroute"><?php echo $cruis->TV['kr_cities']; ?></span>
			</div>
		</div>
		<div class="w-clearfix siright">
			<div class="w-clearfix sitemtophead">
				<div class="sitemteopheadkauta">каюта</div>
				<div class="sitemheadmest">мест</div>
				<div class="sitemheadstoimost">стоимость</div>
			</div>
			<div class="sitemtable">
				<?php
				$hover=true;
				foreach($prices as $price)
				{
					if((isset($price->TV['cr_price_places_free']))and($price->TV['cr_price_places_free']!=''))
					{
						?>
						<div class="w-clearfix sitableitem <?php
						$hover = !$hover;
						if($hover) echo ' hov';
						?>">
							<div class="sitablekauta"><?php echo $price->TV['cr_price_name']; ?></div>
							<div class="sitablemest"><?php echo $price->TV['cr_price_places_free']; ?></div>
							<div class="sitablestoimost"><?php echo $price->TV['cr_price_price']; ?></div>
						</div>

						<?php
					}

				}
				?>

			</div>
			<div class="sirightbutton1">
				<a class="siteminfoa" href="/bronirovanie.html?cruis_id=<?php echo $cruis->id; ?>"><div class="sitembutton">Забронировать</div></a>
			</div>
		</div>
	</div>

	<?php

}