<div class="row">
  <div class="col-12 p-0">
    <?php if ($signature != '' && $meeting_id != ''): ?>

      <style>
          /* To hide */
          #zmmtg-root {
              display: none;
              position: unset;
          }

          .mini-layout-body {
              margin: 0 !important;
              margin-top: 10px !important;
              margin-left: 24% !important;
          }

          .mini-layout-body-title {
              margin: 0 !important;
          }

          .meeting-app {
              width: 100% !important;
          }

          #wc-loading {
              width: 100% !important;
              /* height: 100% !important;*/
          }

          body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-footer{ margin-left: auto; margin-bottom: -48px; }
          .main-footer{ padding: 0; border-top:0; }
          .join-dialog { margin-bottom: -48px; }
          .meeting-header { z-index: 99; }
          .main-footer.footer{ z-index:98; }
          .footer-button-base__button{ box-shadow:none; border:0; border-radius:0; }
          .dropup .dropdown-toggle::after, .dropdown-toggle::after, .meeting-info-container { display:none; }
      </style>


      <div class="ReactModal__Body--open">
          <!-- added on import -->
          <div id="zmmtg-root"></div>
          <div id="aria-notify-area"></div>

          <!-- added on meeting init -->
          <div class="ReactModalPortal"></div>
          <div class="ReactModalPortal"></div>
          <div class="ReactModalPortal"></div>
          <div class="ReactModalPortal"></div>
          <div class="global-pop-up-box"></div>
          <div class="sharer-controlbar-container sharer-controlbar-container--hidden"></div>
      </div>

      <!-- Dependencies for client view and component view -->
      <script src="https://source.zoom.us/3.13.1/lib/vendor/react.min.js"></script>
      <script src="https://source.zoom.us/3.13.1/lib/vendor/react-dom.min.js"></script>
      <script src="https://source.zoom.us/3.13.1/lib/vendor/redux.min.js"></script>
      <script src="https://source.zoom.us/3.13.1/lib/vendor/redux-thunk.min.js"></script>
      <script src="https://source.zoom.us/3.13.1/lib/vendor/lodash.min.js"></script>

      <!-- CDN for client view -->
      <script src="https://source.zoom.us/3.13.1/zoom-meeting-3.13.1.min.js"></script>
      <!-- <link type="text/css" rel="stylesheet" href="https://source.zoom.us/3.13.1/css/bootstrap.css" /> -->
      <link type="text/css" rel="stylesheet" href="https://source.zoom.us/3.13.1/css/react-select.css" />

      <script>
          console.log(JSON.stringify(ZoomMtg.checkSystemRequirements()));

          // it's option if you want to change the WebSDK dependency link resources. setZoomJSLib must be run at first
          ZoomMtg.setZoomJSLib("https://source.zoom.us/3.13.1/lib", "/av"); // CDN version defaul

          ZoomMtg.preLoadWasm();
          // ZoomMtg.prepareJssdk();
          const zoomMeetingSDK = document.getElementById('zmmtg-root');

          // To hide
          zoomMeetingSDK.style.display = 'none';

          // To show
          zoomMeetingSDK.style.display = 'block';
          ZoomMtg.init({
              leaveUrl: base_url + 'teacher/learning/index/<?php echo $sub_kelas->sub_kelas_id; ?>',
              //webEndpoint: meetingConfig.webEndpoint,
              //disableCORP: !window.crossOriginIsolated, // default true
              disablePreview: true, // default false
              externalLinkPage: base_url + 'teacher/learning/index/<?php echo $sub_kelas->sub_kelas_id; ?>',
              success: function() {
                  ZoomMtg.i18n.load('en-US');
                  ZoomMtg.i18n.reload('en-US');
                  ZoomMtg.join({
                      sdkKey: '<?php echo $client_id; ?>',
                      signature: '<?php echo $signature; ?>', // role in SDK signature needs to be 1
                      meetingNumber: '<?php echo $meeting_id; ?>',
                      passWord: '<?php echo $meeting_password; ?>',
                      userName: '<?php echo $user_name; ?>',
                      userEmail: '<?php echo $user_email; ?>',
                      success: function(res) {
                          console.log("join meeting success");
                          console.log("get attendeelist");
                          ZoomMtg.getAttendeeslist({});
                          ZoomMtg.getCurrentUser({
                              success: function(res) {
                                  console.log("success getCurrentUser", res.result.currentUser);
                              },
                          });
                      },
                      error: function(res) {
                          console.log(res);
                      },
                  });
              },
              error: function(res) {
                  console.log(res);
              },
          });
      </script>
    <?php endif; ?>
  </div>
</div>