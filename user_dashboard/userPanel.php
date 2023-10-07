<?php
session_start();
?>
<!DOCTYPE html>
<html class="dark" lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- ICONS -->
  <script src="../vendor/js/web.js"></script>
  <!-- STYLESHEET -->
  <link rel="icon" href="../vendor/images/pie-chart.png">
  <link rel="stylesheet" href="../vendor/css/userPanel.css" />
  <link rel="stylesheet" type="text/css" href="../vendor/Fontawesome/css/all.min.css" />
  <link rel="stylesheet" href="dtJquery.css" />

  <title>Profile</title>
</head>

<body>
  <!-- Jquery -->
  <div class="container">
    <div class="sidebar active">
      <div class="menu-btn">
        <i class="ph-bold ph-caret-left"></i>
      </div>
      <div class="head">
        <div class="user-img">
          <img src="../vendor/images/crescian.jpg" alt="" />
        </div>
        <div class="user-details">
          <p class="title"><?php echo $_SESSION['dept_code']; ?></p>
          <p class="name"><?php echo $_SESSION['username']; ?></p>
        </div>
      </div>
      <div class="nav">
        <div class="menu">
          <p class="title">Main</p>
          <ul>
            <li>
              <a href="#">
                <i class="icon ph-bold ph-house-simple"></i>
                <span class="text">Dashboard</span>
              </a>
            </li>
            <li>
              <a href="#">
                <i class="icon fa-regular fa-envelope"></i>
                <span class="text">Notification</span>
                <i class="arrow ph-bold ph-caret-down"></i>
              </a>
              <ul class="sub-menu append-notif">
                <li>
                  <span class="position-absolute total-notif-${value.app_id}"><i class="fa-solid fa-arrows-rotate fa-spin"></i></span>
                  <a href="#">
                    <span class="text">Physical Security</span>
                  </a>
                </li>
                <li>
                  <span class="position-absolute total-notif-${value.app_id}"><i class="fa-solid fa-arrows-rotate fa-spin"></i></span>
                  <a href="#">
                    <span class="text">IT Repair and Request</span>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <span class="text">Info Security</span>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
        <div class="menu">
          <p class="title">Settings</p>
          <ul>
            <li>
              <a href="#">
                <i class="icon ph-bold ph-gear"></i>
                <span class="text">Settings</span>
              </a>
            </li>
          </ul>
        </div>
        <div class="menu">
          <p class="title theme">Dark Mode</p>
          <ul>
            <li>
              <a href="#" class="dark">
                <img src="../vendor/images/moon.png" id="icon" />
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="menu">
        <p class="title">Account</p>
        <ul>
          <li>
            <a href="#">
              <i class="icon ph-bold ph-info"></i>
              <span class="text">Help</span>
            </a>
          </li>
          <li>
            <a href="../index.php">
              <i class="icon ph-bold ph-sign-out"></i>
              <span class="text">Home</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="date-container">
      <p id="datetime"></p>
    </div>
    <div class="menu-container-details">
      <div class="menu-name">Notification</div>
      <div class="main-content">
        <div class="card">
          <div class="card-header">
            <div class="Request">
              <i class="fa-solid fa-box-archive"></i> Request
            </div>
            <div class="Approved">
              <i class="fa-solid fa-folder-closed"></i> Approved
            </div>
            <div class="Noted">
              <i class="fa-solid fa-trash-can-arrow-up"></i> Noted
            </div>
          </div>
          <hr />
          <div class="card-body">
            <table id="example" class="display" style="width:100%">
              <thead class="thead">
                <tr>
                  <th>System</th>
                  <th>Remarks</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody class="tbody">
                <tr>
                  <td>IT Software</td>
                  <td>ERP Connection Error</td>
                  <td></td>
                </tr>
              </tbody>
              <tfoot class="tfooter">
                <tr>
                  <th>System</th>
                  <th>Remarks</th>
                  <th>Status</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript" src="../vendor/JQuery/jquery.min.js"></script>
  <script type="text/javascript" src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js" integrity="sha512-8Z5++K1rB3U+USaLKG6oO8uWWBhdYsM3hmdirnOEWp8h2B1aOikj5zBzlXs8QOrvY9OxEnD2QDkbSKKpfqcIWw==" crossorigin="anonymous"></script> -->
  <script src="../vendor/js/script.js"></script>
  <script>
    function loadNavLink() {
      $('.loadNavLink', '#nav-item-append').html("");
      $.ajax({
        url: '../controller/notification_controller/notification_module_contr.class_v2.php',
        type: 'POST',
        dataType: 'JSON',
        data: {
          action: 'load_nav_link',
          fullname
        },
        success: function(result) {
          result.length == 0 ? $('.card').hide() : $('.card').show();
          let html = '';
          var loop_count = 0;
          var icon_adjust = 0;
          $.each(result, (key, value) => {
            if (key === 0) {
              loadAssignatory(value.app_id);
            }
            setInterval(() => {
              loadTotalCount(value.app_id);
            }, 500);
            loop_count++;
            let icon_preview = '';
            icon_adjust += 4;
            icon_preview = `<span class="position-absolute top-${icon_adjust} start-100 translate-middle badge rounded-pill bg-danger total-notif-${value.app_id}"></span>`;
            html += `<li><a class="notification-nav-link" onclick="loadAssignatory('${value.app_id}')"><input type="hidden" class="system_section_${value.app_id}" id="system_section_${value.app_id}" value="${value.app_name}">${value.app_name}${icon_preview}</a></li>`;
          });
          $('.append-notif').append(html);
          $('.notification-nav-link:first').addClass('active');
          $('.notification-nav-link').click(function(e) {
            e.preventDefault();
            $('.notification-nav-link').removeClass('active');
            $(this).addClass('active').fadeIn();
          });
        }
      });
    }
  </script>
</body>

</html>