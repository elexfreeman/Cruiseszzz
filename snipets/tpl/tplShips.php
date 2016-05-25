<div class="poph1" id="gogo">Теплоходы</div>
<div class="shipsblock">
    <?php
    /*todo: скачать все картинки теплоходов*/
    $ships=$this->GetShipsList();
    foreach ($ships as $ship)
    {
       ?>
        <div class="ship ">
            <div class="shipleft">
                <div class="w-clearfix shlefthead">
                    <div class="shleftexthead"><?php echo $ship->title; ?></div>
                    <a href="<?php echo $ship->url; ?>" class="shheadlink">Подробнее</a>
                </div>
                <img src="<?php echo $ship->TV['t_title_img'];  ?>" class="shipimg">
            </div>
            <div class="shright">
                <div class="shipdiscription">
                    <p><?php echo $ship->TV['t_description'];  ?></p>
                </div>
                <div class="w-clearfix shgallery">

                    <a href="#" class="w-lightbox w-inline-block gallimglnk">
                        <div class="shgalimg"
                            style="background-image: url('<?php echo tmbImg($ship->TV['t_gl_img_01'],'&h=120&w=152&zc=1');  ?>')"
                            >
                        </div>
                        <script type="application/json" class="w-json">{
                                "items": [
                                    {
                                        "url": "<?php echo $ship->TV['t_gl_img_01'];  ?>",
                                        "fileName": "11.jpg",
                                        "origFileName": "1.jpg",
                                        "width": 472,
                                        "height": 473,
                                        "size": 108711,
                                        "caption": "<?php echo $ship->title; ?>",
                                        "type": "image"
                                    },
                                    {
                                        "url": "<?php echo $ship->TV['t_gl_img_02'];  ?>",
                                        "fileName": "11.jpg",
                                        "origFileName": "1.jpg",
                                        "width": 472,
                                        "height": 473,
                                        "size": 108711,
                                        "caption": "<?php echo $ship->title; ?>",
                                        "type": "image"
                                    },
                                    {
                                        "url": "<?php echo $ship->TV['t_gl_img_03'];  ?>",
                                        "fileName": "11.jpg",
                                        "origFileName": "1.jpg",
                                        "width": 472,
                                        "height": 473,
                                        "size": 108711,
                                        "caption": "<?php echo $ship->title; ?>",
                                        "type": "image"
                                    },
                                    {
                                        "url": "<?php echo $ship->TV['t_gl_img_04'];  ?>",
                                        "fileName": "11.jpg",
                                        "origFileName": "1.jpg",
                                        "width": 472,
                                        "height": 473,
                                        "size": 108711,
                                        "caption": "<?php echo $ship->title; ?>",
                                        "type": "image"
                                    }
                                ]
                            }</script>
                    </a>

                    <a href="#" class="w-lightbox w-inline-block gallimglnk">
                        <div class="shgalimg"
                             style="background-image: url('<?php echo tmbImg($ship->TV['t_gl_img_02'],'&h=120&w=152&zc=1');  ?>')"
                            >
                        </div>
                        <script type="application/json" class="w-json">{
                                "items": [
                                    {
                                        "url": "<?php echo $ship->TV['t_gl_img_02'];  ?>",
                                        "fileName": "11.jpg",
                                        "origFileName": "1.jpg",
                                        "width": 472,
                                        "height": 473,
                                        "size": 108711,
                                        "caption": "<?php echo $ship->title; ?>",
                                        "type": "image"
                                    },
                                    {
                                        "url": "<?php echo $ship->TV['t_gl_img_01'];  ?>",
                                        "fileName": "11.jpg",
                                        "origFileName": "1.jpg",
                                        "width": 472,
                                        "height": 473,
                                        "size": 108711,
                                        "caption": "<?php echo $ship->title; ?>",
                                        "type": "image"
                                    },
                                    {
                                        "url": "<?php echo $ship->TV['t_gl_img_03'];  ?>",
                                        "fileName": "11.jpg",
                                        "origFileName": "1.jpg",
                                        "width": 472,
                                        "height": 473,
                                        "size": 108711,
                                        "caption": "<?php echo $ship->title; ?>",
                                        "type": "image"
                                    },
                                    {
                                        "url": "<?php echo $ship->TV['t_gl_img_04'];  ?>",
                                        "fileName": "11.jpg",
                                        "origFileName": "1.jpg",
                                        "width": 472,
                                        "height": 473,
                                        "size": 108711,
                                        "caption": "<?php echo $ship->title; ?>",
                                        "type": "image"
                                    }
                                ]
                            }</script>
                    </a>

                    <a href="#" class="w-lightbox w-inline-block gallimglnk">
                        <div class="shgalimg"
                             style="background-image: url('<?php echo tmbImg($ship->TV['t_gl_img_03'],'&h=120&w=152&zc=1');  ?>')"
                            >
                        </div>
                        <script type="application/json" class="w-json">{
                                "items": [
                                    {
                                        "url": "<?php echo $ship->TV['t_gl_img_03'];  ?>",
                                        "fileName": "11.jpg",
                                        "origFileName": "1.jpg",
                                        "width": 472,
                                        "height": 473,
                                        "size": 108711,
                                        "caption": "<?php echo $ship->title; ?>",
                                        "type": "image"
                                    },
                                    {
                                        "url": "<?php echo $ship->TV['t_gl_img_02'];  ?>",
                                        "fileName": "11.jpg",
                                        "origFileName": "1.jpg",
                                        "width": 472,
                                        "height": 473,
                                        "size": 108711,
                                        "caption": "<?php echo $ship->title; ?>",
                                        "type": "image"
                                    },
                                    {
                                        "url": "<?php echo $ship->TV['t_gl_img_01'];  ?>",
                                        "fileName": "11.jpg",
                                        "origFileName": "1.jpg",
                                        "width": 472,
                                        "height": 473,
                                        "size": 108711,
                                        "caption": "<?php echo $ship->title; ?>",
                                        "type": "image"
                                    },
                                    {
                                        "url": "<?php echo $ship->TV['t_gl_img_04'];  ?>",
                                        "fileName": "11.jpg",
                                        "origFileName": "1.jpg",
                                        "width": 472,
                                        "height": 473,
                                        "size": 108711,
                                        "caption": "<?php echo $ship->title; ?>",
                                        "type": "image"
                                    }
                                ]
                            }</script>
                    </a>

                    <a href="#" class="w-lightbox w-inline-block gallimglnk">
                        <div class="shgalimg"
                             style="background-image: url('<?php echo tmbImg($ship->TV['t_gl_img_04'],'&h=120&w=152&zc=1');  ?>')"
                            >
                        </div>
                        <script type="application/json" class="w-json">{
                                "items": [
                                    {
                                        "url": "<?php echo $ship->TV['t_gl_img_04'];  ?>",
                                        "fileName": "11.jpg",
                                        "origFileName": "1.jpg",
                                        "width": 472,
                                        "height": 473,
                                        "size": 108711,
                                        "caption": "<?php echo $ship->title; ?>",
                                        "type": "image"
                                    },
                                    {
                                        "url": "<?php echo $ship->TV['t_gl_img_02'];  ?>",
                                        "fileName": "11.jpg",
                                        "origFileName": "1.jpg",
                                        "width": 472,
                                        "height": 473,
                                        "size": 108711,
                                        "caption": "<?php echo $ship->title; ?>",
                                        "type": "image"
                                    },
                                    {
                                        "url": "<?php echo $ship->TV['t_gl_img_03'];  ?>",
                                        "fileName": "11.jpg",
                                        "origFileName": "1.jpg",
                                        "width": 472,
                                        "height": 473,
                                        "size": 108711,
                                        "caption": "<?php echo $ship->title; ?>",
                                        "type": "image"
                                    },
                                    {
                                        "url": "<?php echo $ship->TV['t_gl_img_01'];  ?>",
                                        "fileName": "11.jpg",
                                        "origFileName": "1.jpg",
                                        "width": 472,
                                        "height": 473,
                                        "size": 108711,
                                        "caption": "<?php echo $ship->title; ?>",
                                        "type": "image"
                                    }
                                ]
                            }</script>
                    </a>



                </div>
            </div>
        </div>
    <?php
    }
    ?>

</div>