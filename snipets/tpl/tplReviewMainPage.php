<div class="reviews">
    <h2 class="reviewsh2">Отзывы наших клиентов</h2>
    <?php
    $reviews = $this->GetForMainPage();
    $right = false;
    foreach ($reviews as $review)
    {
      ?>
        <div class="reviewitem">
            <div class="reviewitemtext"><?php echo $review->TV['review_text'];?></div>
            <div class="reviewbottm <?php if($right) echo ' right '; ?>"></div>
            <div class="w-clearfix reviewitemautor">
                <img src="/images/<?php echo $review->TV['review_photo'];?>" class="reviewpersonimg <?php if($right) echo ' right '; ?>">

                <div class="reviewutortext <?php if($right) echo ' right '; ?>">
                    <span class="reviewfio"><?php echo $review->TV['review_name'];?></span><br>
                    <?php echo $review->TV['review_work'];?>
                </div>
            </div>
        </div>
      <?php
        $right=!$right;
    }
?>
    <div class="rewbottom"><a href="/reviews/" class="rewlinkb">читать все отзывы</a></div>
</div>