function ncPreviewReset() {
  document.getElementById("nc-preview-error-wrp").style.display = "";
  document.getElementById("nc-preview-error-txt").innerHTML = "";
  document.getElementById("nc-preview-link").href = "#";
  document.getElementById("nc-preview-image-wrp").classList.remove("nc-preview-image-wrp");
  document.getElementById("nc-preview-image").src = "";
  document.getElementById("nc-preview-details-title").innerHTML = "";
  document.getElementById("nc-preview-details-description").innerHTML = "";
}
