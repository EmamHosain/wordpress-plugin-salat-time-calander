<?php
$cities = [
    "Rajshahi",
    "Chattogram",
    "Narayanganj",
    "Dhaka",
    "Khulna",
    "Barishal",
    "Sylhet",
    "Cumilla",
    "Narsingdi"
];
$months = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
];

$current_year = date('Y'); // 2024
$current_month = date('F'); // Novermber
$current_day = '';

?>

<style>
    .container {
        width: 100%;
        display: flex;
        gap: 2rem;
    }
</style>
<?php ob_start() ?>
<div class="wrap">
    <h1>API Data Table</h1>
    <!-- sorting start -->
    <div class="tablenav top">


        <!-- sort by city -->
        <div class="alignleft actions bulkactions">
            <label for="filter-by-city" class="screen-reader-text">Select city</label>
            <select name="action" id="filter-by-city">
                <option value="" select>Select city</option>
                <?php foreach ($cities as $index => $city): ?>
                    <option value="<?php echo $city; ?>"><?php echo $city; ?></option>
                <?php endforeach; ?>
            </select>
        </div>




        <div class="alignleft actions">
            <!-- sort by month -->
            <label for="filter-by-month" class="screen-reader-text">Filter by month</label>
            <select name="month" id="filter-by-month">
                <option selected="selected" value="">Select month</option>
                <?php foreach ($months as $index => $month): ?>
                    <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
                <?php endforeach; ?>
            </select>



            <!-- sort by year -->
            <label class="screen-reader-text" for="filter-by-year">Filter by year</label>
            <select name="year" id="filter-by-year" class="postform">
                <option value="">Selelct year</option>
                <?php for ($year = 2000; $year <= $current_year; $year++): ?>
                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                <?php endfor; ?>
            </select>


            <label class="screen-reader-text" for="filter-by-day">Filter by day</label>
            <select name="day" id="filter-by-day" class="postform">
                <option value="">Select day</option>
                <?php for ($day = 1; $day <= 31; $day++): ?>
                    <option value="<?php echo str_pad($day, 2, '0', STR_PAD_LEFT); ?>">
                        <?php echo str_pad($day, 2, '0', STR_PAD_LEFT); ?>
                    </option>
                <?php endfor; ?>
            </select>
            <button id="reset_btn"
                style="padding:0.5rem 1rem;color:white;background-color:green; cursor:pointer">Reset</button>
        </div>
        <br class="clear">
    </div>
    <!-- sorting end -->

    <div class="container">
        <div style="width:70%">
            <table border="1" cellspacing="0" cellpadding="5" class="widefat fixed striped" style="margin-top:1rem">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Fajr</th>
                        <th scope="col">Sunrise</th>
                        <th scope="col">Dhuhr</th>
                        <th scope="col">Asr</th>
                        <th scope="col">Sunset</th>
                        <th scope="col">Maghrib</th>
                        <th scope="col">Isha</th>
                    </tr>
                </thead>
                <tbody id="salat_table_body">
                    <?php if (!empty($data['data'])): ?>
                        <?php foreach ($data['data'] as $item): ?>
                            <tr>
                                <td><?php echo $item['date']['gregorian']['date']; ?></td>
                                <td><?php echo formatDate($item['timings']['Fajr']); ?></td>
                                <td><?php echo formatDate($item['timings']['Sunrise']); ?></td>
                                <td><?php echo formatDate($item['timings']['Dhuhr']); ?></td>
                                <td><?php echo formatDate($item['timings']['Asr']); ?></td>
                                <td><?php echo formatDate($item['timings']['Sunset']); ?></td>
                                <td><?php echo formatDate($item['timings']['Maghrib']); ?></td>
                                <td><?php echo formatDate($item['timings']['Isha']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td style="text-center" colspan="8">No data found.</td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>




        <!-- current data -->
        <div>
            <h1>current data</h1>
            <?php foreach ($current_data as $item): ?>
                <p>Date : <?php echo $item['date']['gregorian']['date']; ?></p>
                <p>Weekday : <?php echo $item['date']['gregorian']['weekday']['en']; ?></p>
                <p>Salat Time :</p>
                <ul>
                    <li>Fajr : <?php echo formatDate($item['timings']['Fajr']); ?></li>
                    <li>Sunrise : <?php echo formatDate($item['timings']['Sunrise']); ?></li>
                    <li>Dhuhr : <?php echo formatDate($item['timings']['Dhuhr']); ?></li>
                    <li>Asr : <?php echo formatDate($item['timings']['Asr']); ?></li>
                    <li>Sunset : <?php echo formatDate($item['timings']['Sunset']); ?></li>
                    <li>Maghrib : <?php echo formatDate($item['timings']['Maghrib']); ?></li>
                    <li>Isha : <?php echo formatDate($item['timings']['Isha']); ?></li>
                </ul>
            <?php endforeach; ?>
        </div>

    </div>


</div>
<?php echo ob_get_clean() ?>