<div id="main" class="wrapper containers bg_content">
  <div class="row">
    <div id="sidebar" class="col-sm-2 sidebar">
      
      <?php $this->load->view("students/kelas_list"); ?>
      
    </div>

    <div id="assignment-main" class="col-sm-10">
        <div class="row">
            <div class="col">
                <p class="text-center mb-0 p-2">
                    <span id="countdown">00:00:00</span>
                </p>
            </div>
        </div>
        <div class="row">
            
            <div class="col-sm-6 p-4" style="background: #D8D0CB; min-height: 700px;">
                <table id="assignment-add" class="w-100">
                  <tr>
                    <td class="left">Assignment</td>
                    <td colspan="5">
                      <input type="text" name="assignment" id="assignment" class="w-100" value="<?php echo $assignment->assignment; ?>" disabled="true" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="p-2"></td>
                  </tr>
                  <tr>
                    <td class="left">Assign to</td>
                    <td colspan="5">
                      <input type="text" class="mr-2" value="<?php echo $assignment->nama_kelas; ?>" disabled="true" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="p-2"></td>
                  </tr>
                  <tr>
                    <td class="left">Instruction</td>
                    <td colspan="5"></td>
                  </tr>
                  <tr>
                    <td colspan="6" class="p-0">
                      <textarea class="form-control rounded-0" disabled="true" rows="5" style="background: #ECE9E7;" ><?php echo $assignment->intruksi; ?></textarea>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="p-2"></td>
                  </tr>
                  <tr>
                    <td class="left" colspan="3">Due date</td>
                    <td>
                      <input type="text" class="mr-2" id="created_date" value="<?php echo $assignment->crated_date; ?>" disabled="true" />
                    </td>
                    <td class="left text-center">To</td>
                    <td class="pl-2">
                      <input type="text" id="due_date" value="<?php echo $assignment->due_date; ?>" disabled="true" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="p-2"></td>
                  </tr>
                  <tr>
                    <td colspan="4" class="left">Number of Questions</td>
                    <td colspan="2">
                      <?php
                      $dataArray = json_decode($assignment->questions, true);
                      $jumlahData = count($dataArray); ?>
                      <input type="text" value="<?php echo $jumlahData; ?>" disabled="true" style="width: 108px; text-align: center;" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="p-2"></td>
                  </tr>
                  <tr>
                    <td colspan="4" class="left">Total Score</td>
                    <td colspan="2">
                      <input type="text" class="w-20" name="total_score" id="total_score" value="<?php echo $assignment->total_score; ?>" disabled="true" style="width: 108px; text-align: center;" />
                    </td>
                  </tr>
                </table>
            </div>

            <div class="col-sm-6 p-4" style="background: #CDC5C5; min-height: 700px;">
                <div id="dynamic_field">

                  <?php
                  $questionsArray = json_decode($assignment->questions);  // Pastikan ini valid
                  $no = 1; $totalNilai = 0;

                  $assignmentResponses = isset($responses) ? $responses : [];

                  foreach ($questionsArray as $item) {
                      $scoreProperty = "question_" . $item->id  . "_score";
                      $scoreValue = isset($item->$scoreProperty) ? $item->$scoreProperty : '';

                      $nilaiValue = isset($assignmentResponses[$item->id]['nilai']) ? floatval($assignmentResponses[$item->id]['nilai']) : 0;

                      $instruksiProperty = "question_" . $item->id . "_intruksi";
                      $instruksiValue = isset($item->$instruksiProperty) ? $item->$instruksiProperty : '';

                      echo "<div data-id='$item->id' class='form-group'>
                          <div class='form-group mb-0'>
                              <label class='font-weight-normal' style='background:#ddd; padding: 2px 10px;'>Question $no</label>
                              <input type='text' name='question_{$item->id}_score' class='question-score' value='$scoreValue/{$nilaiValue}' style='width: 60px;text-align: center;' disabled='true'>
                          </div>

                          <div class='instruksi'>";
                              echo $instruksiValue;
                      echo "</div>";

                      if (isset($assignmentResponses[$item->id]) && $assignmentResponses[$item->id]['akun_id'] == $this->session->user_id ) {
                          $response = $assignmentResponses[$item->id];
                          echo "<div class='row recordrtc'>
                              <div class='col-sm-6'>
                                  <div class='audio-container'>
                                      <audio preload='auto' src='".base_url()."uploads/audio/".$response['file_name']."' controls=''></audio>
                                  </div>
                              </div>
                              <div class='col-sm-6'>
                                  <div class='btn-group float-right'>
                                      <button class='btn btn-primary btn-sm rounded-0 start-recording'>
                                          <i class='fas fa-play-circle'></i> Start Recording
                                      </button>

                                      <div style='display: none;' class='recording-actions'>
                                          <button class='btn btn-secondary btn-sm rounded-0 upload-to-server'>
                                              <i class='fas fa-upload'></i> Submit
                                          </button>
                                      </div>
                                  </div>
                              </div>
                          </div>";
                      } else {
                          echo "<div class='row recordrtc'>
                              <div class='col-sm-6'>
                                  <div class='audio-container'></div>
                              </div>
                              <div class='col-sm-6'>
                                  <div class='btn-group float-right'>
                                      <button class='btn btn-primary btn-sm rounded-0 start-recording'>
                                          <i class='fas fa-play-circle'></i> Start Recording
                                      </button>

                                      <div style='display: none;' class='recording-actions'>
                                          <button class='btn btn-secondary btn-sm rounded-0 upload-to-server'>
                                              <i class='fas fa-upload'></i> Submit
                                          </button>
                                      </div>
                                  </div>
                              </div>
                          </div>";
                      }

                      echo "</div>";

                      $no++; $totalNilai += $nilaiValue;
                  }

                  echo "<div id='total-nilai'>
                      <p class='text-center'>
                          <span id='tampilkan-nilai'>T0TAL NILAI: $totalNilai</span>
                      </p>
                  </div>";
                  ?>
                </div>
            </div>
        </div>
    </div>
    
  </div>
</div>
    

<script>
    $(document).ready(function() {

        function convertToISO(dateStr) {
            if (!dateStr) {
                console.error('Date string is undefined or null.');
                return null;
            }
            return dateStr.replace(' ', 'T');
        }

        var createdDate = $("input#created_date").val();
        var dueDate = $("input#due_date").val();

        if (!createdDate || !dueDate) {
            console.error('One or both date values are missing.');
            return;
        }

        createdDate = convertToISO(createdDate);
        dueDate = convertToISO(dueDate);

        if (!Date.parse(createdDate) || !Date.parse(dueDate)) {
            console.error('Invalid date format after conversion.');
            return;
        }

        var startTime = new Date(createdDate).getTime();
        var endTime = new Date(dueDate).getTime();

        var x = setInterval(function() {

            var now = new Date().getTime();
            var distance = endTime - now;

            // Calculate days, hours, minutes, and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24)); // Adding days calculation
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display countdown including days
            $("#countdown").html(
                (days > 0 ? days + "d " : '') + 
                (hours < 10 ? '0' : '') + hours + ":" +
                (minutes < 10 ? '0' : '') + minutes + ":" +
                (seconds < 10 ? '0' : '') + seconds
            );

            // If the countdown is over, trigger the expiration event
            if (distance < 0) {
                clearInterval(x);
                $("#countdown").html("EXPIRED");

                var form_data = new FormData();
                form_data.append("status","finished");

                var assignment_id = window.location.pathname.split('/')[4];
                console.log(assignment_id);
                form_data.append('assignment_id', assignment_id);

                form_data.append("csrf_token", $.cookie('csrf_cookie'));

                $.ajax({
                    url: base_url + 'students/assignment/update_status',
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    processData: false,
                    contentType: false,
                    cache:false,
                    success: function(response) {
                        console.log('Success:', response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
        }, 1000);
    });

</script>

<script src="<?php echo base_url(); ?>assets/js/RecordRTC.js"></script>
<script src="<?php echo base_url(); ?>assets/js/gif-recorder.js"></script>
<script src="<?php echo base_url(); ?>assets/js/getScreenId.js"></script>
<script src="<?php echo base_url(); ?>assets/js/DetectRTC.js"> </script>

<script>
    (function() {
        var params = {},
            r = /([^&=]+)=?([^&]*)/g;

        function d(s) {
            return decodeURIComponent(s.replace(/\+/g, ' '));
        }

        var match, search = window.location.search;
        while (match = r.exec(search.substring(1))) {
            params[d(match[1])] = d(match[2]);

            if(d(match[2]) === 'true' || d(match[2]) === 'false') {
                params[d(match[1])] = d(match[2]) === 'true' ? true : false;
            }
        }

        window.params = params;
    })();
</script>

<script>
    document.querySelectorAll('.start-recording').forEach(function(button, index) {
        button.onclick = function() {
            var recordingDIV = button.closest('.recordrtc');
            var recordingActions = recordingDIV.querySelector('.recording-actions');
            var uploadButton = recordingActions.querySelector('.upload-to-server');

            if (button.innerHTML === '<i class="fas fa-stop-circle"></i> Stop Recording') {
                button.classList.remove('btn-danger');
                button.classList.add('btn-primary');

                button.disabled = true;
                button.disableStateWaiting = true;
                setTimeout(function() {
                    button.disabled = false;
                    button.disableStateWaiting = false;
                }, 2 * 1000);

                button.innerHTML = '<i class="fas fa-play-circle"></i> Start Recording';

                function stopStream() {
                    if (button.stream && button.stream.stop) {
                        button.stream.stop();
                        button.stream = null;
                    }
                }

                if (button.recordRTC) {
                    button.recordRTC.stopRecording(function(url) {
                        button.recordingEndedCallback(url);
                        stopStream();
                        saveToDiskOrOpenNewTab(button.recordRTC, recordingActions);
                    });
                }

                return;
            }

            resetUploadButton(uploadButton);

            button.disabled = true;

            var commonConfig = {
                onMediaCaptured: function(stream) {
                    button.stream = stream;
                    if (button.mediaCapturedCallback) {
                        button.mediaCapturedCallback();
                    }

                    button.classList.remove('btn-primary');
                    button.classList.add('btn-danger');

                    button.innerHTML = '<i class="fas fa-stop-circle"></i> Stop Recording';
                    button.disabled = false;
                },
                onMediaStopped: function() {
                    button.classList.remove('btn-danger');
                    button.classList.add('btn-primary');

                    button.innerHTML = '<i class="fas fa-play-circle"></i> Start Recording';

                    if (!button.disableStateWaiting) {
                        button.disabled = false;
                    }
                },
                onMediaCapturingFailed: function(error) {
                    commonConfig.onMediaStopped();
                }
            };

            captureAudio(commonConfig, recordingDIV);

            button.mediaCapturedCallback = function() {
                button.recordRTC = RecordRTC(button.stream, {
                    type: 'audio',
                    bufferSize: typeof params === 'undefined' || params.bufferSize === undefined ? 0 : parseInt(params.bufferSize),
                    sampleRate: typeof params === 'undefined' || params.sampleRate === undefined ? 44100 : parseInt(params.sampleRate),
                    leftChannel: params && params.leftChannel || false,
                    disableLogs: params && params.disableLogs || false,
                    recorderType: DetectRTC.browser.name === 'Edge' ? StereoAudioRecorder : null
                });

                button.recordingEndedCallback = function(url) {
                    var audio = new Audio();
                    audio.src = url;
                    audio.controls = true;

                    var audioContainer = recordingDIV.querySelector('.audio-container');
                    audioContainer.innerHTML = '';
                    audioContainer.appendChild(audio);

                    if (audio.paused) audio.play();

                    audio.onended = function() {
                        audio.pause();
                        audio.src = URL.createObjectURL(button.recordRTC.blob);
                    };
                };

                button.recordRTC.startRecording();
            };
        };
    });

    function resetUploadButton(uploadButton) {
        uploadButton.disabled = true;
        uploadButton.innerHTML = '<i class="fas fa-upload"></i> Submit';
        uploadButton.style.display = 'none'; // Sembunyikan kembali

        uploadButton.onclick = null;
    }

    function captureAudio(config) {
        captureUserMedia({ audio: true }, function(audioStream) {
            config.onMediaCaptured(audioStream);

            audioStream.onended = function() {
                config.onMediaStopped();
            };
        }, function(error) {
            config.onMediaCapturingFailed(error);
        });
    }

    function captureUserMedia(mediaConstraints, successCallback, errorCallback) {
        navigator.mediaDevices.getUserMedia(mediaConstraints)
            .then(successCallback)
            .catch(errorCallback);
    }

    function saveToDiskOrOpenNewTab(recordRTC, recordingActions) {
        recordingActions.style.display = 'block';

        var uploadButton = recordingActions.querySelector('.upload-to-server');
        uploadButton.disabled = false;
        uploadButton.style.display = 'block'; // Tampilkan tombol upload setelah perekaman selesai

        uploadButton.onclick = function() {
            if (!recordRTC) return alert('No recording found.');
            this.disabled = true;

            var itemId = this.closest('.form-group').getAttribute('data-id');

            uploadToServer(recordRTC, function(progress, fileURL) {
                console.log(fileURL);

                if (progress === 'ended') {
                    var downloadUrl = fileURL.replace('students/assignment/upload_audio_video', 'uploads/audio/');

                    uploadButton.disabled = false;
                    uploadButton.innerHTML = '<i class="fas fa-download"></i> Download';
                    uploadButton.classList.remove('btn-secondary');
                    uploadButton.classList.add('btn-warning');
                    uploadButton.onclick = function() {
                        window.open(downloadUrl); 
                    };
                    return;
                }

                uploadButton.innerHTML = progress;
            }, itemId);
        };
    }

    function uploadToServer(recordRTC, callback, itemId) {
        var blob = recordRTC instanceof Blob ? recordRTC : recordRTC.blob;
        var fileType = blob.type.split('/')[0] || 'audio';
        var fileName = (Math.random() * 1000).toString().replace('.', '');

        if (fileType === 'audio') {
            fileName += '.wav';
        }

        var formData = new FormData();
        formData.append(fileType + '-filename', fileName);
        formData.append(fileType + '-blob', blob);
        formData.append('question_id', itemId);

        var uriSegment4 = window.location.pathname.split('/')[4];
        console.log(uriSegment4);
        formData.append('assignment_id', uriSegment4);

        callback('Uploading ' + fileType + ' recording to server.');

        var upload_url = base_url + 'students/assignment/upload_audio_video';

        makeXMLHttpRequest(upload_url, formData, function(progress) {
            if (progress !== 'upload-ended') {
                callback(progress);
                return;
            }

            callback('ended', upload_url + fileName);
        });
    }

    function makeXMLHttpRequest(url, data, callback) {
        var request = new XMLHttpRequest();
        request.onreadystatechange = function() {
            if (request.readyState == 4 && request.status == 200) {
                callback('upload-ended');
            }
        };

        request.upload.onloadstart = function() {
            callback('Upload started...');
        };

        request.upload.onprogress = function(event) {
            callback('Upload Progress ' + Math.round(event.loaded / event.total * 100) + "%");
        };

        request.upload.onload = function() {
            callback('progress-ended');
        };

        request.upload.onerror = function(error) {
            callback('Failed to upload to server');
            console.error('XMLHttpRequest failed', error);
        };

        request.upload.onabort = function(error) {
            callback('Upload aborted.');
            console.error('XMLHttpRequest aborted', error);
        };

        request.open('POST', url);
        request.send(data);
    }
</script>






<?php $this->load->view("students/kelas_list_js"); ?>

