var wrd_thisReservationMustBeDeletedDueToNewModifications = "This reservation must be deleted due to new modifications";
var wrd_becauseReservationsOverlapAndContainTheSameInformationTheyWillBeCombinedIntoOne = "Because the reservations overlap and contain the same information, they will be combined into one";
var wrd_theDatesOfThisReservationWillBePostponedDueToNewModifications = "The dates of this reservation will be postponed due to new modifications";
var wrd_newModificationsWillSplitThisReservation = "New modifications will split this reservation";
var wrd_inOrderForNewAdjustmentsToBeMadeThisBookingMustBeRejected = "In order for new adjustments to be made, this booking must be rejected (in other words, canceled)";
var wrd_name = "Name";
var wrd_email = "Email";
var wrd_phoneNumber = "Phone number";
var wrd_cleaning = "Cleaning";
var wrd_maintenance = "Maintenance";
var wrd_reconstruction = "Reconstruction";
var wrd_other = "Other";
function permissionNeededDictionary(
  thisReservationMustBeDeletedDueToNewModifications,
  becauseReservationsOverlapAndContainTheSameInformationTheyWillBeCombinedIntoOne,
  theDatesOfThisReservationWillBePostponedDueToNewModifications,
  newModificationsWillSplitThisReservation,
  inOrderForNewAdjustmentsToBeMadeThisBookingMustBeRejected,
  wName,
  wEmail,
  wPhoneNumber,
  wCleaning,
  wMaintenance,
  wReconstruction,
  wOther
) {
  wrd_thisReservationMustBeDeletedDueToNewModifications = thisReservationMustBeDeletedDueToNewModifications;
  wrd_becauseReservationsOverlapAndContainTheSameInformationTheyWillBeCombinedIntoOne = becauseReservationsOverlapAndContainTheSameInformationTheyWillBeCombinedIntoOne;
  wrd_theDatesOfThisReservationWillBePostponedDueToNewModifications = theDatesOfThisReservationWillBePostponedDueToNewModifications;
  wrd_newModificationsWillSplitThisReservation = newModificationsWillSplitThisReservation;
  wrd_inOrderForNewAdjustmentsToBeMadeThisBookingMustBeRejected = inOrderForNewAdjustmentsToBeMadeThisBookingMustBeRejected;
  wrd_name = wName;
  wrd_email = wEmail;
  wrd_phoneNumber = wPhoneNumber;
  wrd_cleaning = wCleaning;
  wrd_maintenance = wMaintenance;
  wrd_reconstruction = wReconstruction;
  wrd_other = wOther;
}
function permissionNeededData(s_id, s_f_d, s_f_m, s_f_y, s_t_d, s_t_m, s_t_y, p_Name, p_Email, p_Phone, p_Guest, p_Notes, p_F_d, p_F_m, p_F_y, p_T_d, p_T_m, p_T_y, p_fistday, p_lastday, p_deposit, p_fullAmount, p_category, acceptFnc) {
  document.getElementById("permission-needed-to-change-accept-details-id").innerHTML = s_id;
  document.getElementById("permission-needed-to-change-accept-details-f-d").innerHTML = s_f_d;
  document.getElementById("permission-needed-to-change-accept-details-f-m").innerHTML = s_f_m;
  document.getElementById("permission-needed-to-change-accept-details-f-y").innerHTML = s_f_y;
  document.getElementById("permission-needed-to-change-accept-details-t-d").innerHTML = s_t_d;
  document.getElementById("permission-needed-to-change-accept-details-t-m").innerHTML = s_t_m;
  document.getElementById("permission-needed-to-change-accept-details-t-y").innerHTML = s_t_y;
  document.getElementById("permission-needed-to-change-accept-name").innerHTML = p_Name;
  document.getElementById("permission-needed-to-change-accept-email").innerHTML = p_Email;
  document.getElementById("permission-needed-to-change-accept-phone").innerHTML = p_Phone;
  document.getElementById("permission-needed-to-change-accept-guests").innerHTML = p_Guest;
  document.getElementById("permission-needed-to-change-accept-notes").innerHTML = p_Notes;
  document.getElementById("permission-needed-to-change-accept-f-d").innerHTML = p_F_d;
  document.getElementById("permission-needed-to-change-accept-f-m").innerHTML = p_F_m;
  document.getElementById("permission-needed-to-change-accept-f-y").innerHTML = p_F_y;
  document.getElementById("permission-needed-to-change-accept-t-d").innerHTML = p_T_d;
  document.getElementById("permission-needed-to-change-accept-t-m").innerHTML = p_T_m;
  document.getElementById("permission-needed-to-change-accept-t-y").innerHTML = p_T_y;
  document.getElementById("permission-needed-to-change-accept-firstday").innerHTML = p_fistday;
  document.getElementById("permission-needed-to-change-accept-lastday").innerHTML = p_lastday;
  document.getElementById("permission-needed-to-change-accept-deposit").innerHTML = p_deposit;
  document.getElementById("permission-needed-to-change-accept-full-amount").innerHTML = p_fullAmount;
  document.getElementById("permission-needed-to-change-accept-category").innerHTML = p_category;
  document.getElementById("permission-needed-to-change-btn-accept").value = acceptFnc;
}

// bb- stands for blocking booking
function permissionNeededBookingRender(bb_task, bb_name, bb_email, bb_phone, bb_from, bb_to) {
  var changeWrp = document.createElement("div");
  changeWrp.setAttribute("class", "p-n-t-c-lwrp");
  var changeHead = document.createElement("button");
  changeHead.setAttribute("class", "p-n-t-c-lhead");
  changeHead.setAttribute("onclick", "permissionNeededOnclick('"+ bb_from +"-"+ bb_to +"')");
  var changeDatesWrp = document.createElement("div");
  changeDatesWrp.setAttribute("class", "p-n-t-c-ldates-wrp");
  var changeDatesTxt = document.createElement("p");
  changeDatesTxt.setAttribute("class", "p-n-t-c-ldates-txt");
  changeDatesTxt.innerHTML = bb_from +" - "+ bb_to;
  var changeStatusWrp = document.createElement("div");
  changeStatusWrp.setAttribute("class", "p-n-t-c-lstatus-wrp");
  var changeStatusTxt = document.createElement("p");
  changeStatusTxt.setAttribute("class", "p-n-t-c-lstatus-txt");
  changeStatusTxt.classList.add("p-n-t-c-lstatus-txt-"+ bb_task);
  changeStatusTxt.innerHTML = bb_task;
  var changeDropdownIcon = document.createElement("div");
  changeDropdownIcon.setAttribute("class", "p-n-t-c-ldropdown-icon");
  var changeDetails = document.createElement("div");
  changeDetails.setAttribute("class", "p-n-t-c-ldetails");
  changeDetails.setAttribute("id", "p-n-t-c-ldetails-"+ bb_from +"-"+ bb_to);
  var changeDetailsRowDesc = document.createElement("div");
  changeDetailsRowDesc.setAttribute("class", "p-n-t-c-ldetails-row");
  var changeDetailsDescTxtSize = document.createElement("div");
  changeDetailsDescTxtSize.setAttribute("class", "p-n-t-c-ldetails-row-txt-size");
  var changeDetailsDescTxt = document.createElement("p");
  changeDetailsDescTxt.setAttribute("class", "p-n-t-c-ldetails-row-txt-desc");
  if (bb_task == "delete") {
    changeDetailsDescTxt.innerHTML = wrd_thisReservationMustBeDeletedDueToNewModifications;
  } else if (bb_task == "connect") {
    changeDetailsDescTxt.innerHTML = wrd_becauseReservationsOverlapAndContainTheSameInformationTheyWillBeCombinedIntoOne;
  } else if (bb_task == "shorten") {
    changeDetailsDescTxt.innerHTML = wrd_theDatesOfThisReservationWillBePostponedDueToNewModifications;
  } else if (bb_task == "split") {
    changeDetailsDescTxt.innerHTML = wrd_newModificationsWillSplitThisReservation;
  } else if (bb_task == "reject") {
    changeDetailsDescTxt.innerHTML = wrd_inOrderForNewAdjustmentsToBeMadeThisBookingMustBeRejected;
  }
  if (bb_task != "reject") {
    var changeDetailsRowName = document.createElement("div");
    changeDetailsRowName.setAttribute("class", "p-n-t-c-ldetails-row");
    var changeDetailsNameTitle = document.createElement("p");
    changeDetailsNameTitle.setAttribute("class", "p-n-t-c-ldetails-row-title");
    changeDetailsNameTitle.innerHTML = wrd_name +":";
    var changeDetailsNameTxtSize = document.createElement("div");
    changeDetailsNameTxtSize.setAttribute("class", "p-n-t-c-ldetails-row-txt-size");
    var changeDetailsNameTxt = document.createElement("p");
    changeDetailsNameTxt.setAttribute("class", "p-n-t-c-ldetails-row-txt");
    changeDetailsNameTxt.innerHTML = bb_name;
    var changeDetailsRowEmail = document.createElement("div");
    changeDetailsRowEmail.setAttribute("class", "p-n-t-c-ldetails-row");
    var changeDetailsEmailTitle = document.createElement("p");
    changeDetailsEmailTitle.setAttribute("class", "p-n-t-c-ldetails-row-title");
    changeDetailsEmailTitle.innerHTML = wrd_email +":";
    var changeDetailsEmailTxtSize = document.createElement("div");
    changeDetailsEmailTxtSize.setAttribute("class", "p-n-t-c-ldetails-row-txt-size");
    var changeDetailsEmailTxt = document.createElement("p");
    changeDetailsEmailTxt.setAttribute("class", "p-n-t-c-ldetails-row-txt");
    changeDetailsEmailTxt.innerHTML = bb_email;
    var changeDetailsRowPhone = document.createElement("div");
    changeDetailsRowPhone.setAttribute("class", "p-n-t-c-ldetails-row");
    var changeDetailsPhoneTitle = document.createElement("p");
    changeDetailsPhoneTitle.setAttribute("class", "p-n-t-c-ldetails-row-title");
    changeDetailsPhoneTitle.innerHTML = wrd_phoneNumber +":";
    var changeDetailsPhoneTxtSize = document.createElement("div");
    changeDetailsPhoneTxtSize.setAttribute("class", "p-n-t-c-ldetails-row-txt-size");
    var changeDetailsPhoneTxt = document.createElement("p");
    changeDetailsPhoneTxt.setAttribute("class", "p-n-t-c-ldetails-row-txt");
    changeDetailsPhoneTxt.innerHTML = bb_phone;
  }
  changeWrp.appendChild(changeHead);
  changeHead.appendChild(changeDatesWrp);
  changeDatesWrp.appendChild(changeDatesTxt);
  changeHead.appendChild(changeStatusWrp);
  changeStatusWrp.appendChild(changeStatusTxt);
  changeStatusWrp.appendChild(changeDropdownIcon);
  changeWrp.appendChild(changeDetails);
  changeDetails.appendChild(changeDetailsRowDesc);
  changeDetailsRowDesc.appendChild(changeDetailsDescTxtSize);
  changeDetailsDescTxtSize.appendChild(changeDetailsDescTxt);
  if (bb_task != "reject") {
    changeDetails.appendChild(changeDetailsRowName);
    changeDetailsRowName.appendChild(changeDetailsNameTitle);
    changeDetailsRowName.appendChild(changeDetailsNameTxtSize);
    changeDetailsNameTxtSize.appendChild(changeDetailsNameTxt);
    changeDetails.appendChild(changeDetailsRowEmail);
    changeDetailsRowEmail.appendChild(changeDetailsEmailTitle);
    changeDetailsRowEmail.appendChild(changeDetailsEmailTxtSize);
    changeDetailsEmailTxtSize.appendChild(changeDetailsEmailTxt);
    changeDetails.appendChild(changeDetailsRowPhone);
    changeDetailsRowPhone.appendChild(changeDetailsPhoneTitle);
    changeDetailsRowPhone.appendChild(changeDetailsPhoneTxtSize);
    changeDetailsPhoneTxtSize.appendChild(changeDetailsPhoneTxt);
  }
  document.getElementById("permission-needed-to-change-list").appendChild(changeWrp);
}

// bts- stands for blocking technical shutdown
function permissionNeededTechnicalShutdownRender(bts_task, bts_category, bts_notes, bts_from, bts_to) {
  var changeWrp = document.createElement("div");
  changeWrp.setAttribute("class", "p-n-t-c-lwrp");
  var changeHead = document.createElement("button");
  changeHead.setAttribute("class", "p-n-t-c-lhead");
  changeHead.setAttribute("onclick", "permissionNeededOnclick('"+ bts_from +"-"+ bts_to +"')");
  var changeDatesWrp = document.createElement("div");
  changeDatesWrp.setAttribute("class", "p-n-t-c-ldates-wrp");
  var changeDatesTxt = document.createElement("p");
  changeDatesTxt.setAttribute("class", "p-n-t-c-ldates-txt");
  var changeDatesTxtBold = document.createElement("b");
  if (bts_category == "cleaning") {
    changeDatesTxtBold.innerHTML = wrd_cleaning;
  } else if (bts_category == "maintenance") {
    changeDatesTxtBold.innerHTML = wrd_maintenance;
  } else if (bts_category == "reconstruction") {
    changeDatesTxtBold.innerHTML = wrd_reconstruction;
  } else {
    changeDatesTxtBold.innerHTML = wrd_other;
  }
  changeDatesTxt.appendChild(changeDatesTxtBold);
  changeDatesTxt.innerHTML = changeDatesTxt.innerHTML +" ("+ bts_from +" - "+ bts_to +")";
  var changeStatusWrp = document.createElement("div");
  changeStatusWrp.setAttribute("class", "p-n-t-c-lstatus-wrp");
  var changeStatusTxt = document.createElement("p");
  changeStatusTxt.setAttribute("class", "p-n-t-c-lstatus-txt");
  changeStatusTxt.classList.add("p-n-t-c-lstatus-txt-"+ bts_task);
  changeStatusTxt.innerHTML = bts_task;
  var changeDropdownIcon = document.createElement("div");
  changeDropdownIcon.setAttribute("class", "p-n-t-c-ldropdown-icon");
  var changeDetails = document.createElement("div");
  changeDetails.setAttribute("class", "p-n-t-c-ldetails");
  changeDetails.setAttribute("id", "p-n-t-c-ldetails-"+ bts_from +"-"+ bts_to);
  var changeDetailsRowDesc = document.createElement("div");
  changeDetailsRowDesc.setAttribute("class", "p-n-t-c-ldetails-row");
  var changeDetailsDescTxtSize = document.createElement("div");
  changeDetailsDescTxtSize.setAttribute("class", "p-n-t-c-ldetails-row-txt-size");
  var changeDetailsDescTxt = document.createElement("p");
  changeDetailsDescTxt.setAttribute("class", "p-n-t-c-ldetails-row-txt-desc");
  if (bts_task == "delete") {
    changeDetailsDescTxt.innerHTML = wrd_thisReservationMustBeDeletedDueToNewModifications;
  } else if (bts_task == "connect") {
    changeDetailsDescTxt.innerHTML = wrd_becauseReservationsOverlapAndContainTheSameInformationTheyWillBeCombinedIntoOne;
  } else if (bts_task == "shorten") {
    changeDetailsDescTxt.innerHTML = wrd_theDatesOfThisReservationWillBePostponedDueToNewModifications;
  } else if (bts_task == "split") {
    changeDetailsDescTxt.innerHTML = wrd_newModificationsWillSplitThisReservation;
  }
  var changeDetailsRowNotes = document.createElement("div");
  changeDetailsRowNotes.setAttribute("class", "p-n-t-c-ldetails-row");
  var changeDetailsNotesTxtSize = document.createElement("div");
  changeDetailsNotesTxtSize.setAttribute("class", "p-n-t-c-ldetails-row-txt-size");
  var changeDetailsNotesTxt = document.createElement("p");
  changeDetailsNotesTxt.setAttribute("class", "p-n-t-c-ldetails-row-txt");
  changeDetailsNotesTxt.innerHTML = bts_notes;
  changeWrp.appendChild(changeHead);
  changeHead.appendChild(changeDatesWrp);
  changeDatesWrp.appendChild(changeDatesTxt);
  changeHead.appendChild(changeStatusWrp);
  changeStatusWrp.appendChild(changeStatusTxt);
  changeStatusWrp.appendChild(changeDropdownIcon);
  changeWrp.appendChild(changeDetails);
  changeDetails.appendChild(changeDetailsRowDesc);
  changeDetailsRowDesc.appendChild(changeDetailsDescTxtSize);
  changeDetailsDescTxtSize.appendChild(changeDetailsDescTxt);
  changeDetails.appendChild(changeDetailsRowNotes);
  changeDetailsRowNotes.appendChild(changeDetailsNotesTxtSize);
  changeDetailsNotesTxtSize.appendChild(changeDetailsNotesTxt);
  document.getElementById("permission-needed-to-change-list").appendChild(changeWrp);
}

var openedNBPermNeeded = "";
function permissionNeededOnclick(id) {
  for (var pNeededNum = 0; pNeededNum < document.getElementsByClassName("p-n-t-c-ldetails").length; pNeededNum++) {
    document.getElementsByClassName("p-n-t-c-ldetails")[pNeededNum].classList.remove("p-n-t-c-ldetails-show");
  }
  if (openedNBPermNeeded != id && id != "") {
    openedNBPermNeeded = id;
    document.getElementById("p-n-t-c-ldetails-"+ id).classList.add("p-n-t-c-ldetails-show");
  } else {
    openedNBPermNeeded = "";
  }
}

function permissionNeededAccept(fncSelect) {
  if (fncSelect == "new-booking") {
    editorCalendarNewBookingWChanges();
  } else if (fncSelect == "update-booking") {
    editorCalendarUpdateBookingWChanges(
      document.getElementById("permission-needed-to-change-accept-details-f-d").textContent,
      document.getElementById("permission-needed-to-change-accept-details-f-m").textContent,
      document.getElementById("permission-needed-to-change-accept-details-f-y").textContent,
      document.getElementById("permission-needed-to-change-accept-details-t-d").textContent,
      document.getElementById("permission-needed-to-change-accept-details-t-m").textContent,
      document.getElementById("permission-needed-to-change-accept-details-t-y").textContent
    );
  } else if (fncSelect == "add-technical-shutdown") {
    editorCalendarAddTechnicalShutdownWChanges();
  } else if (fncSelect == "update-technical-shutdown") {
    editorCalendarUpdateTechnicalShutdownWChanges(
      document.getElementById("permission-needed-to-change-accept-details-f-d").textContent,
      document.getElementById("permission-needed-to-change-accept-details-f-m").textContent,
      document.getElementById("permission-needed-to-change-accept-details-f-y").textContent,
      document.getElementById("permission-needed-to-change-accept-details-t-d").textContent,
      document.getElementById("permission-needed-to-change-accept-details-t-m").textContent,
      document.getElementById("permission-needed-to-change-accept-details-t-y").textContent
    );
  } else if (fncSelect == "booking-update-request") {
    bookingUpdateConfirmWChanges(
      document.getElementById("permission-needed-to-change-accept-details-id").textContent,
    );
  }
}
