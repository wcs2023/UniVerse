<?php
    $title = "Degree recommendation System";
    $this->view('actors/students/includes/header2.php');
?>

<?php

                $districts = ['Colombo','Galle','Matara','Gampaha','Kaluthara','Hambanthota','Rathnapura','Badulla','Kandy','Mathale','Nuwara Eliya','Jaffna','Kilinochchi','Mannar','Vavuniya','Mulathivu','Batticaloa','Ampara','Trincomalee','Kurunegala','Puttalama','Anuradhapura','Polonnaruwa','Monaragala','kegalle'];
            ?>

<body>
    <section class="degree-bg">    
            <div class="degree-content">
                <h1>Discover Your Ideal Degree Path</h1>
                <p>Our advanced recommendation system analyzes your Z-score and individual interests to provide personalized degree suggestions. Explore suitable universities and career pathways tailored to your strengths, aspirations, and long-term goals.</p>
        </div>
    </section>

    <?php 
        if(!empty($errors)): ?>
        <div class="error-messages">
            <ul>
                <?php foreach($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <section class="suggestions">
        <h1>Get suggestions</h1>
        <p>Explore degree choices tailored to your strengths and ambitions.</p>
        <form method="POST" action="<?=BASE_URL?>views/actors/students/degree_result.view.php">
            <div class="form-group">
                <label for="zscore">Z-score</label>
                <input type="text" id="zscore" name="zscore" placeholder="Enter your Z-score" required 
                value = "<?= isset($old['zscore']) ? htmlspecialchars($old['zscore']) : '' ?>">

            </div>
            <div class="form-group">
                <label for="stream">Stream</label>
                <select name="stream" id="stream" required>
                    <option value="" disabled <?= empty($old['stream'])?'selected' : '' ?>>Choose one...</option>
                    <?php 
                    $streams = ['maths'=>'Maths','bio'=>'Bio Science','arts'=>'Arts','commerce'=>'Commerce','tech'=>'Technology'];
                    foreach($streams as $value => $label): ?>
                        <option value="<?= $value ?>" <?= (isset($old['stream']) && $old['stream'] === $value) ? 'selected' : '' ?>> <?= $label ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>            

            <div class="form-group">
                <label for="district">District</label>
                <select name="district" id="district" required>
                    <option value="" disabled <?=empty($old['district'])?'selected' : '' ?>>Select your district</option>
                    <?php foreach($districts as $district): ?>
                        <option value="<?= strtolower($district) ?>" <?= (isset($old['district']) && $old['district'] === strtolower($district)) ? 'selected' : '' ?>> <?= $district?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn submit-btn">Submit</button>
        </form>
    </section>
</body>