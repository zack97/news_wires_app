document.querySelectorAll(".add-fav-btn").forEach((btn) => {
  btn.addEventListener("click", (e) => {
    e.preventDefault();
    const articleId = btn.dataset.id;

    fetch(`/favorites/add/${articleId}`, {
      method: "POST",
    })
      .then((res) => res.json())
      .then((data) => {
        alert(data.message);
      });
  });
});
