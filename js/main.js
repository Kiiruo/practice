const headerEl = document.getElementById("header");

window.addEventListener("scroll", function () {
  const scrollPos = window.scrollY;
  const screenWidth = window.innerWidth;

  if (screenWidth > 430) { 
    if (scrollPos > 24) {
      headerEl.classList.add("header_mini");
    } else {
      headerEl.classList.remove("header_mini");
    }
  } else {

    headerEl.classList.remove("header_mini");
  }
});

