<!-- NEW -->

<div class="filter">
    <h2 class="h2filtr">Поиск круизов:</h2>
    <div class="loader">
        <img src="/images/loader.GIF" style="
    width: 69px;
    margin: 85px auto;
    display: block;
">
    </div>

    <div class="w-form">
        <form id="email-form" name="email-form" data-name="Email Form" class="w-clearfix">
            <div class="filterblock">
                <label for="startcity" class="filterlabel">Город отправления:</label>
                <select name="startcity" id="city_start" data-name="startcity"  class="w-select filterinput">
                    <option value="">-</option>
                    <?php
                    $citys=$this->GetShipsCityStartList();
                    foreach($citys as $key=>$city)
                    {
                        ?>
                        <option value="<?php echo $city; ?>"><?php echo $city; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="filterblock sityp">
                <label for="startcity-2" class="filterlabel">Город посещения:</label>
                <select   id="city" name="startcity-2" data-name="Startcity 2" class="w-select filterinput">
                    <option value="">-</option>
                    <?php
                    $citys=$this->GetShipsCityList();
                    foreach($citys as $key=>$city)
                    {
                        ?>
                        <option value="<?php echo $city; ?>"><?php echo $city; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="filterblock teploh">
                <label for="ship" class="filterlabel">Теплоход:</label>
                <select id="ship" name="startcity-3" data-name="ship" class="w-select filterinput">
                    <option value="">-</option>
                    <?php
                    $ships=$this->GetShipsList();
                    foreach($ships as $ship)
                    {
                        ?>
                        <option value="<?php echo $ship->id; ?>"><?php echo $ship->title; ?></option>
                        <?php
                    }

                    ?>
                </select>
            </div>
            <div class="w-clearfix filterblock date">
                <label for="field" class="filterlabel">Дата отправления:</label>

                <label class="date-label">С:</label>
                <input
                    type="text"
                    id="date_start"
                    placeholder="с:"
                    name="date_start"
                    required="required" data-name="date_start"
                    class="w-input filterinput small hasDatepicker3 date_picker">

                <label class="date-label">По:</label>
                <input
                    id="date_stop"
                    type="text"
                    placeholder="по:"
                    name="date_stop"
                    required="required"
                    data-name="date_stop"
                    class="w-input filterinput small right hasDatepicker3 date_picker">

            </div>
            <div class="w-clearfix filterblock button">
                <div class="w-checkbox w-clearfix chkvhd">
                    <input id="weekend" type="checkbox" name="weekend" data-name="weekend" class="w-checkbox-input chkvhd1">
                    <label for="weekend" class="w-form-label">Круизы выходного дня</label>
                </div>
                <input type="button" value="Поиск" onclick="Search();" class="w-button searchbutton">
            </div>
        </form>

    </div>
</div>



<!-- ****************************** -->

