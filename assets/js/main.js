var elekDropzone = document.getElementById('elek-dropzone');
var attDropzone = document.getElementById('att-dropzone');
var submitButton = document.getElementById('elekSubmit');

window.Dropzone.options.elekDropzone = {
  paramName: 'elekFiles',
  // uploadMultiple: true,
  autoProcessQueue: false,
  addRemoveLinks: true,
  parallelUploads: 5000,
  init: function () {
    var elekzone = this;
    submitButton.onclick = function (e) {
      // Make sure that the form isn't actually being sent.
      e.preventDefault();
      e.stopPropagation();
      elekzone.processQueue();
    };
  }
}

window.Dropzone.options.attDropzone = {
  paramName: 'attFiles',
  // uploadMultiple: true,
  autoProcessQueue: false,
  addRemoveLinks: true,
  parallelUploads: 5000,
  init: function () {
    var attzone = this;
    this.element.querySelector("button[type=submit]").addEventListener("click", function (e) {
      // Make sure that the form isn't actually being sent.
      e.preventDefault();
      e.stopPropagation();
      sendHash();
      attxone.processQueue();
    });

    this.on('queuecomplete', function() {
      submitButton.click();
    });
  }
}

function sendHash() {
  const hash = md5("ipek60" + Math.floor((Math.random() * 10000) + 0.1) + Math.floor((Math.random() * 10000) + 0.1));
  var uri = "./test.php";
  var xhr = new XMLHttpRequest();
  var fd = new FormData();

  xhr.open("POST", uri, true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      alert(xhr.responseText); // handle response.
    }
  };
  fd.append('hash', hash);
  // Initiate a multipart/form-data upload
  xhr.send(fd);
}

// function uploadFiles() {
//   const hash = md5("ipek60" + Math.floor((Math.random() * 10000) + 0.1) + Math.floor((Math.random() * 10000) + 0.1));
//   // console.log(attFileList);
//   // for (const file of attFileList.files) {
//   //   console.log(file);
//   //   // templateATTInput.files.FileList.push(file);    
//   // }
//   // for (const file of elekFileList.files.FileList) {
//   //   templateELEKInput.files.push(file);    
//   // }
// }