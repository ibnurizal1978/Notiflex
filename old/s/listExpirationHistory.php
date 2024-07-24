<?php
require_once 'inc/header.php';

//detail data
$list_id             = Encryption::decode($param[1]);
$category_id         = Encryption::decode($param[2]);
?>

<div class="content-wrapper">
  <div class="content"><!-- Card Profile -->

    <div class="row">
      <div class="col-xl-12">
        <div class="card card-default">
          <div class="card-header">
            <h2 class="mb-5">Expiration History</h2>
          </div>

          <div class="card-body">
            <?php
            $s2 = "SELECT a.list_id, a.category_id, a.list_expiration_id, category_name, date_format(start_date, '%M %d, %Y') as start_date, date_format(expired_date, '%M %d, %Y') as expired_date, datediff(expired_date, CURDATE()) difference, name, photo_name
            FROM tbl_list_expiration a INNER JOIN tbl_category b USING (category_id)  INNER JOIN tbl_list c ON a.held_by = c.id INNER JOIN tbl_list_upload d ON a.list_expiration_id = d.list_expiration_id WHERE a.list_id = '".$list_id."' AND a.category_id = '".$category_id."' AND a.client_id = '".$_SESSION['client_id']."' ORDER BY list_Expiration_id DESC";
            $h2 = mysqli_query($conn, $s2);
            if(mysqli_num_rows($h2) == 0)
            {
              echo 'No expiration data.';
            }else{
              ?>
              <table class="table table-hover table-product" id="list">
                <tr>
                  <th>Expiration Category</th>
                  <th>Active Date</th>
                  <th>Expired Date</th>
                  <th>Held by</th>
                  <th class="text-center">Action</th>
                </tr>
                <?php while($r2 = mysqli_fetch_assoc($h2)) { ?>
                <tr>
                  <td><?php echo $r2['category_name'] ?></td>
                  <td><?php echo $r2['start_date'] ?></td>
                  <td>
                    <?php
                    echo $r2['expired_date'].'<br/>';
                    if($r2['difference']<0) { 
                      echo '<b class="text-danger">ALREADY EXPIRED!</b>';
                    } elseif($r2['difference']<10) { 
                      echo '<small>expired in <b class="text-danger">'.$r2['difference'].' days</b></small>';
                    }else{
                      echo '<small>expired in <b class="text-success">'.$r2['difference'].' days</b></small>';
                    }
                    ?></td>
                  <td><?php echo $r2['name'] ?></td>
                  <td class="text-center">
                    <a class="btn-sm btn-success" href="../list_upload/<?php echo $r2['photo_name'] ?>" target="_blank">view file</a>               
                  </td>
                </tr>           
                <?php } ?>
                </table>
            <?php } ?>

          </div>
        </div>
      </div>
    </div>
  </div>



</div>

<?php require_once 'inc/footer.php' ?>