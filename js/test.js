const appendCategoryButton = document.getElementById("appendCategoryButton");
const categoryPullDown = document.getElementById("categoryPullDown");

appendCategoryButton.addEventListener("click", () => {
    var option = document.createElement("option");
    option.text = "aaa";
    categoryPullDown.appendChild(option);
});