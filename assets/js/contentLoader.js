function loadContent(page) {
  fetch("assets/content/" + page + ".php")
    .then((res) => res.text())
    .then((html) => {
      document.getElementById("main-content").innerHTML = html;
      history.pushState({}, "", "?page=" + page);
    });
}

// // On first load
// window.addEventListener("DOMContentLoaded", () => {
//   const url = new URL(window.location.href);
//   const page = url.searchParams.get("page") || "home";
//   loadContent(page);
// });
