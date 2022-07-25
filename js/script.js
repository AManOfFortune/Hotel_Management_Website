//----------------------------
// CODE TO INCLUDE NAVBAR (AND POTENTIALLY ALL OTHER ELEMENTS) WITH JS
//----------------------------

// fetch('/pages/navbar.html')
// .then(res => res.text())
// .then(text => {
// 	// Convert the HTML string into a document object
// 	let parser = new DOMParser();
// 	let doc = parser.parseFromString(text, 'text/html');

//     document.querySelector("#replace_with_navbar").replaceWith(doc.querySelector("#nav_wrapper"));
// });

//Function to fade navbar background & scrollbar visibility
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {
    document.getElementById("navbar").style.backgroundColor = "var(--primary)";
    document.getElementById("page").classList.remove("hide-scrollbar");
  } else {
    document.getElementById("navbar").style.backgroundColor = "transparent";
    document.getElementById("page").classList.add("hide-scrollbar");
  }
}

// Function to show a modal
function showModal(modalID, closeID) {
   const modal = document.getElementById(modalID);

    // Get all elements with a given class
    const span = document.getElementsByClassName(closeID);

    // When the user clicks on the button, open the modal
    modal.style.display = "block";

    // When the user clicks on an element with the given class, close the modal
    for(let i = 0; i < span.length; i++) {
        span[i].onclick = function() {
            modal.style.display = "none";
        }
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}

// Function to show a picture
function showPicture(picture_path) {
    const modal = document.createElement("div");
    modal.classList.add("modal");
    modal.id = "picture_modal";

    const modal_content = document.createElement("div");
    modal_content.classList.add("picture-modal-content");

    const close = document.createElement("span");
    close.classList.add("close", "closePicture");
    close.innerHTML = "&times;";

    const img = document.createElement("img");
    img.src = picture_path;

    modal_content.appendChild(close);
    modal_content.appendChild(img);
    modal.appendChild(modal_content);

    document.body.appendChild(modal);

    //SHOW, THEN DELETE THE MODAL
    const picture_modal = document.getElementById("picture_modal");
    const span = document.getElementsByClassName("closePicture");
    picture_modal.style.display = "block";

    for(let i = 0; i < span.length; i++) {
        span[i].onclick = function() {
            picture_modal.remove();
        }
    }

    window.onclick = function(event) {
        if (event.target == picture_modal) {
            picture_modal.remove();
        }
    }
}

//Function to insert headings, lists etc. in the news-content textarea
function insertAtCursor(tagToInsert, el = document.getElementById("news_content")) {
    const [start, end] = [el.selectionStart, el.selectionEnd];
    var insertText = "</p><" + tagToInsert + ">REPLACE_ME</" + tagToInsert + "><p>";

    if(tagToInsert == 'a') {
        insertText = "</p><" + tagToInsert + " href='REPLACE_ME_WITH_LINK_URL'>REPLACE_ME_WITH_LINK_TEXT</" + tagToInsert + "><p>";
    } else if (tagToInsert == 'ul' || tagToInsert == 'ol') {
        insertText = "</p><" + tagToInsert + "><li>FIRST_ITEM</li><li>SECOND_ITEM</li><li>THIRD_ITEM</li></" + tagToInsert + "><p>";
    }

    el.setRangeText(insertText, start, end, 'select');
}