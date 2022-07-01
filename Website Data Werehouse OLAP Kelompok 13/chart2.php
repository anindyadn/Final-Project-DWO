<?php include("koneksi.php");
$sql = "SELECT CONCAT('name:\'',d.year,'\'') as store_name, CONCAT('y:', SUM(fs.order_quantity)/(SELECT SUM(order_quantity) AS tot_qty FROM fact_sales)*100) as y , CONCAT('drilldown:', d.year) as drilldown FROM store p JOIN fact_sales fs ON p.storeID = fs.storeID JOIN date_dimens d ON d.dateID = fs.dateID GROUP BY d.year";
$sql1 = mysqli_query($koneksi,$sql);
while($sql2 = mysqli_fetch_assoc($sql1)){
  $chart1[] = '{'.$sql2['store_name'].','.$sql2['y'].','.$sql2['drilldown'].'},';
}
$sqll = "SELECT SUM(fs.order_quantity)/(SELECT SUM(fs1.order_quantity) AS total_penjualan
FROM store p1 JOIN fact_sales fs1
ON p1.storeID = fs1.storeID
JOIN date_dimens d1
ON d1.dateID = fs1.dateID 
WHERE d1.year = d.year
GROUP BY d1.year)*100  AS total_penjualan,(SELECT SUM(fs1.order_quantity) AS total_penjualan
FROM store p1 JOIN fact_sales fs1
ON p1.storeID = fs1.storeID
JOIN date_dimens d1
ON d1.dateID = fs1.dateID 
WHERE d1.year = d.year
GROUP BY d1.year) AS total_pertahun , p.store_name, d.year
FROM store p JOIN fact_sales fs
ON p.storeID = fs.storeID
JOIN date_dimens d
ON d.dateID = fs.dateID
GROUP BY p.store_name, d.year  
ORDER BY `d`.`year`  ASC";
$sqll1 = mysqli_query($koneksi,$sqll);
$k=0;
while($sqll2 = mysqli_fetch_assoc($sqll1)){

  $total_penjualan[] = $sqll2['total_penjualan'];
  $total_pertahun[] = $sqll2['total_pertahun'];
  $tahun[] = $sqll2['year'];
  $store_name[] = $sqll2['store_name'];
}
for ($i=0; $i < count($tahun) ; $i++) { 

    
  if ($i==0) {
  $chart22 = '{name:'.$tahun[$i].',id:'.$tahun[$i].',data:[';
  }
  $chart22 .= '[`'.$store_name[$i].'`,'.$total_penjualan[$i].'],';
  if ($i ==count($tahun)-1 ) {
   $chart22 .=']}'; 
   break;
  }
  if ($tahun[$i] != $tahun[$i+1]) {
    $chart22 .=']},{name:'.$tahun[$i+1].',id:'.$tahun[$i+1].',data:[';
  }
}
  
?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>CodePen - Pie with drilldown</title>
  <link rel="stylesheet" href="./style.css">

</head>
<body>
  <!-- partial:index.partial.html -->
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/data.js"></script>
  <script src="https://code.highcharts.com/modules/drilldown.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>

  <figure class="highcharts-figure">
    <div id="container"></div>
    <p class="highcharts-description">
      Pie chart where the individual slices can be clicked to expose more
      detailed data.
    </p>
  </figure>


  <!-- Data from www.netmarketshare.com. Select Browsers => Desktop share by version. Download as tsv. -->
  <pre id="tsv" style="display:none">Browser Version  Total Market Share
    Microsoft Internet Explorer 8.0  26.61%
    Microsoft Internet Explorer 9.0  16.96%
    Chrome 18.0  8.01%
    Chrome 19.0  7.73%
    Firefox 12  6.72%
    Microsoft Internet Explorer 6.0  6.40%
    Firefox 11  4.72%
    Microsoft Internet Explorer 7.0  3.55%
    Safari 5.1  3.53%
    Firefox 13  2.16%
    Firefox 3.6  1.87%
    Opera 11.x  1.30%
    Chrome 17.0  1.13%
    Firefox 10  0.90%
    Safari 5.0  0.85%
    Firefox 9.0  0.65%
    Firefox 8.0  0.55%
    Firefox 4.0  0.50%
    Chrome 16.0  0.45%
    Firefox 3.0  0.36%
    Firefox 3.5  0.36%
    Firefox 6.0  0.32%
    Firefox 5.0  0.31%
    Firefox 7.0  0.29%
    Proprietary or Undetectable  0.29%
    Chrome 18.0 - Maxthon Edition  0.26%
    Chrome 14.0  0.25%
    Chrome 20.0  0.24%
    Chrome 15.0  0.18%
    Chrome 12.0  0.16%
    Opera 12.x  0.15%
    Safari 4.0  0.14%
    Chrome 13.0  0.13%
    Safari 4.1  0.12%
    Chrome 11.0  0.10%
    Firefox 14  0.10%
    Firefox 2.0  0.09%
    Chrome 10.0  0.09%
    Opera 10.x  0.09%
  Microsoft Internet Explorer 8.0 - Tencent Traveler Edition  0.09%</pre>
  <!-- partial -->
  <script>
    // Create the chart
    Highcharts.chart('container', {
      chart: {
        type: 'pie'
      },
      title: {
        text: 'Grafik Store'
      },
      subtitle: {
        text: 'Click the slices to view versions. Source: <a href="http://statcounter.com" target="_blank">statcounter.com</a>'
      },

      accessibility: {
        announceNewData: {
          enabled: true
        },
        point: {
          valueSuffix: '%'
        }
      },

      plotOptions: {
        series: {
          dataLabels: {
            enabled: true,
            format: '{point.name}: {point.y:.1f}%'
          }
        }
      },

      tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
      },

      series: [
      {
        name: "Browsers",
        colorByPoint: true,
        data: [
        <?php 
        foreach ($chart1 as $key => $data) {
          echo $data;
        }
        ?>
        ]
      }
      ],
      drilldown: {
        series: [
        <?php echo $chart22; ?>
        ]
      }
    });
  </script>

</body>
</html>
