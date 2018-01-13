// let attDropzone = document.getElementById('attDropzone')
// let transferButton = document.getElementById('btnTransfer')

// Dropzone.options.attDropzone = {
//   init: function() {
//     this.on("addedfile", function() {
//       transferButton.style.opacity = 1;
//     });
//   }
// };
let attFileList = document.getElementById('filesATT')
let elekFileList = document.getElementById('filesELEK')


var progressBarPercentage = document.getElementById('progressBar').style.width;
var templateForm = document.getElementById('templateForm');
var templateATTInput = templateForm.firstElementChild;
var templateELEKInput = templateForm.lastElementChild;


function sendFile(file, cakeHash) {
  var uri = "/test.php";
  var xhr = new XMLHttpRequest();
  var fd = new FormData();

  xhr.upload.addEventListener("progress", function (e) {
    if (e.lengthComputable) {
      progressBarPercentage = Math.round((e.loaded * 100) / e.total) + '%';
    }
  }, false);

  xhr.open("POST", uri, true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      alert(xhr.responseText); // handle response.
    }
  };
  fd.append('myFile', file);
  // Initiate a multipart/form-data upload
  xhr.send(fd);
}

function uploadFiles() {
  const hash = md5("ipek60" + Math.floor((Math.random() * 10000) + 0.1) + Math.floor((Math.random() * 10000) + 0.1));
  // console.log(attFileList);
  // for (const file of attFileList.files) {
  //   console.log(file);
  //   // templateATTInput.files.FileList.push(file);    
  // }
  // for (const file of elekFileList.files.FileList) {
  //   templateELEKInput.files.push(file);    
  // }

  
}