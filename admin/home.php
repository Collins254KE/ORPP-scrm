<?php 
session_start();
include("dbconnection.php");
include("checklogin.php");
check_login();

// --- PHP: Prepare Data for Charts ---

// Visitors Today / Overall
$totalVisitors = mysqli_num_rows(mysqli_query($con, "SELECT * FROM usercheck"));
$todayDate = date("Y/m/d");
$todayVisitors = mysqli_num_rows(mysqli_query($con, "SELECT * FROM usercheck WHERE logindate='$todayDate'"));

// Registered Users
$totalUsers = mysqli_num_rows(mysqli_query($con, "SELECT * FROM user"));
$todayUsers = mysqli_num_rows(mysqli_query($con, "SELECT * FROM user WHERE posting_date='".date('Y-m-d')."'"));

// Tickets
$totalTickets = mysqli_num_rows(mysqli_query($con, "SELECT * FROM ticket"));
$pendingTickets = mysqli_num_rows(mysqli_query($con, "SELECT * FROM ticket WHERE status='Open'"));

// --- Daily Visitors (Current Month) ---
$totalDays = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));
$dailyVisitors = array_fill(1, $totalDays, 0);
$visitorsResult = mysqli_query($con, "SELECT logindate FROM usercheck");
while($row = mysqli_fetch_assoc($visitorsResult)){
    $dateArray = explode('/', $row['logindate']);
    if($dateArray[0]==date('Y') && $dateArray[1]==date('m')){
        $day = ltrim($dateArray[2],'0');
        $dailyVisitors[$day]++;
    }
}

// --- Most Visited Departments ---
$deptData = [];
$deptRes = mysqli_query($con,"SELECT department, COUNT(*) AS cnt FROM ticket GROUP BY department");
while($row = mysqli_fetch_assoc($deptRes)){
    $deptData[] = ['name'=>$row['department'], 'y'=>(int)$row['cnt']];
}

// --- Reasons for Visit ---
$reasonData = [];
$reasonRes = mysqli_query($con,"SELECT reason_for_visit, COUNT(*) AS cnt FROM ticket GROUP BY reason_for_visit");
while($row = mysqli_fetch_assoc($reasonRes)){
    $reasonData[] = ['name'=>$row['reason_for_visit'], 'y'=>(int)$row['cnt']];
}

// --- Ratings per Department ---
$ratingData = [];
$ratingRes = mysqli_query($con, "SELECT department, AVG(rating) AS avg_rating FROM ticket GROUP BY department");
while($row = mysqli_fetch_assoc($ratingRes)){
    $ratingData[] = ['name'=>$row['department'], 'y'=>(float)$row['avg_rating']];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CRM | Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link href="../assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/highcharts.js"></script>
<script src="js/exporting.js"></script>
</head>
<body>
<?php include("header.php");?>
<div class="page-container row"> 
<?php include("leftbar.php");?>

<div class="page-content">
    <div class="content sm-gutter">
        <!-- DASHBOARD TILES -->
        <div class="row">
            <!-- Visitors -->
            <div class="col-md-3 col-sm-6">
                <div class="tiles green m-b-10">
                    <div class="tiles-body">
                        <div class="tiles-title">Visitors</div>
                        <div class="widget-stats">
                            <div class="wrapper transparent">
                                <span class="item-title">Overall</span>
                                <span class="item-count"><?php echo $totalVisitors; ?></span>
                            </div>
                        </div>
                        <div class="widget-stats">
                            <div class="wrapper last">
                                <span class="item-title">Today</span>
                                <span class="item-count"><?php echo $todayVisitors; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Registered Users -->
            <div class="col-md-3 col-sm-6">
                <div class="tiles blue m-b-10">
                    <div class="tiles-body">
                        <div class="tiles-title">Registered Users</div>
                        <div class="widget-stats">
                            <div class="wrapper transparent">
                                <span class="item-title">All</span>
                                <span class="item-count"><?php echo $totalUsers; ?></span>
                            </div>
                        </div>
                        <div class="widget-stats">
                            <div class="wrapper last">
                                <span class="item-title">Today</span>
                                <span class="item-count"><?php echo $todayUsers; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tickets -->
            <div class="col-md-3 col-sm-6">
                <div class="tiles red m-b-10">
                    <div class="tiles-body">
                        <div class="tiles-title">Tickets</div>
                        <div class="widget-stats">
                            <div class="wrapper transparent">
                                <span class="item-title">All</span>
                                <span class="item-count"><?php echo $totalTickets; ?></span>
                            </div>
                        </div>
                        <div class="widget-stats">
                            <div class="wrapper last">
                                <span class="item-title">Pending</span>
                                <span class="item-count"><?php echo $pendingTickets; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END DASHBOARD TILES -->

        <!-- DASHBOARD CHARTS -->
        <div class="row">
            <!-- Daily Visitors -->
            <div class="col-lg-6">
                <div class="panel panel-red">
                    <div class="panel-heading">Daily Visitors - <?php echo date("F Y"); ?></div>
                    <div class="panel-body">
                        <div id="dailyVisitors" style="height:400px;"></div>
                    </div>
                </div>
            </div>

            <!-- Most Visited Departments -->
            <div class="col-lg-6">
                <div class="panel panel-blue">
                    <div class="panel-heading">Most Visited Departments</div>
                    <div class="panel-body">
                        <div id="departmentChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Reasons for Visit -->
            <div class="col-lg-6">
                <div class="panel panel-green">
                    <div class="panel-heading">Reasons for Visit</div>
                    <div class="panel-body">
                        <div id="reasonChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>

            <!-- Ratings per Department -->
            <div class="col-lg-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">Average Ratings per Department</div>
                    <div class="panel-body">
                        <div id="ratingChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

<script>
$(function(){
    // Daily Visitors Line Chart
    Highcharts.chart('dailyVisitors', {
        chart: { type: 'line' },
        title: { text: 'Daily Visitors for <?php echo date("F Y"); ?>' },
        xAxis: { categories: <?php echo json_encode(array_map(fn($d)=>"Day $d", array_keys($dailyVisitors))); ?> },
        yAxis: { title: { text: 'Visitors Count' } },
        series: [{ name: 'Visitors', data: <?php echo json_encode(array_values($dailyVisitors)); ?> }]
    });

    // Most Visited Departments Column Chart
    Highcharts.chart('departmentChart', {
        chart: { type: 'column' },
        title: { text: 'Most Visited Departments' },
        xAxis: { type: 'category' },
        yAxis: { title: { text: 'Number of Visits' } },
        series: [{ name: 'Visits', data: <?php echo json_encode($deptData); ?>, dataLabels: { enabled:true } }]
    });

    // Reasons for Visit Pie Chart
    Highcharts.chart('reasonChart', {
        chart: { type: 'pie' },
        title: { text: 'Reasons for Visit' },
        series: [{
            name: 'Count',
            colorByPoint: true,
            data: <?php echo json_encode($reasonData); ?>
        }]
    });

    // Ratings per Department Column Chart
    Highcharts.chart('ratingChart', {
        chart: { type: 'column' },
        title: { text: 'Average Ratings per Department' },
        xAxis: { type: 'category' },
        yAxis: { title: { text: 'Average Rating' }, min:0, max:5 },
        series: [{ 
            name: 'Rating', 
            data: <?php echo json_encode($ratingData); ?>, 
            dataLabels: { enabled:true, format: '{point.y:.1f}' } 
        }]
    });
});
</script>

</body>
</html>
