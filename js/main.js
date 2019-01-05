function showMore(id) {
  $.get("php/ops.php?getID=" + id, res => {
    console.log(res);

    let project = res.data;
    $("#portfolio-modal h2").html(project.name);
    $("#portfolio-modal p").html(project.description);
    $("#portfolio-modal img").attr(
      "src",
      project.image.substring(1, project.image.length)
    );
    $("#portfolio-modal .tags").html("");
    project.tags.forEach(tag => {
      $("#portfolio-modal .tags").append(
        `<a href='index.php?tag=${tag}'>${tag}</a>`
      );
    });
  });
}
