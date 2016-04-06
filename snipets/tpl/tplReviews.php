<div class="reviews page">
    <div class="contaktscallbox review">
        <div class="callformtitle review4">
            <span style="font-size: 28px;"><strong id="review_thx">Оставить отзыв</strong></span>
        </div>
        <div class="w-form contactscallform review3">
            <form id="SendReview" name="email-form-4" data-name="Email Form 4" class="w-clearfix">
                <div class="cfdiv1 rev6">
                    <label for="r_name" class="cflabel">Ваше имя:</label>
                    <input name="action" value="SendReview" type="hidden">
                    <input id="review_name"
                            type="text"
                            name="review_name"
                            required="required"
                            data-name="Cf Name 2"
                            class="w-input cfinput revew6">
                </div>
                <div class="cfdiv1"></div>
                <textarea id="review_text" placeholder="Example Text" name="review_text" class="w-input"></textarea>
                <input onclick="Reviews.Send();"
                    type="button" value="Отправить" data-wait="Please wait..." class="w-button cfbutton review">
            </form>
            <div class="w-form-done"><p>Thank you! Your submission has been received!</p></div>
            <div class="w-form-fail"><p>Oops! Something went wrong while submitting the form</p></div>
        </div>
    </div>
    <h2 class="reviewsh2">Отзывы наших клиентов</h2>

    <?php
    foreach ($this->info as $review)
    {
        if($review->TV['review_ok']=='Да')
        {
            ?>
            <div class="reviewitem">
                <div class="reviewitemtext"><?php echo $review->TV['review_text']; ?>
                </div>
                <div class="reviewbottm"></div>
                <div class="w-clearfix reviewitemautor">
                    <img src="<?php
                    if($review->TV['review_photo']=='')
                    {
                       echo '/images/no-photo.png' ;
                    }
                    else
                    echo '/images/'.$review->TV['review_photo'];
                    ?>" class="reviewpersonimg">

                    <div class="reviewutortext">
                        <span class="reviewfio"><?php echo $review->TV['review_name']; ?></span><br>
                        <?php echo $review->TV['review_work']; ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>


</div>