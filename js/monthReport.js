const line = document.getElementById("line");
const title = document.getElementById("title");
const timeFormArea = document.getElementById("time_form_area");
const appendCategory = document.getElementById("appendCategory");
const appendCategoryButton = document.getElementById("appendCategoryButton");
const contents = document.getElementById("contents");
const categoryPullDown = document.getElementById("categoryPullDown");
const time = document.getElementsByClassName("time");
const appendButton = document.getElementById("appendButton");
const calculateButton = document.getElementById("calculateButton");

var addition = `
<div class="graphArea">
<div class="date">
    <p>日付</p>
    <input type="date">
    </div>
<div class="category">
    <select id="category" name="category">
        <option>動画編集</option>
        <option>Web構築</option>
    </select>
</div>
<div class="input">
    <input type="text" class="time" name="time"/>
    <p>時間</p>
</div>
`;

timeFormArea.addEventListener("click", () => {
    if (document.getElementById("inputTime") != null) {
    } else {
        var input_time = document.createElement("input");
        input_time.type = "text";
        input_time.id = "inputTime";
        input_time.style.width = "90px";
        input_time.style.height = "50px";
        input_time.style.fontSize = "40px";
        timeFormArea.innerHTML = "";
        timeFormArea.appendChild(input_time);
        input_time.focus();
    }
});

document.body.addEventListener("keydown", (e) => {
    const inputTime = document.getElementById("inputTime");
    if (e.key == "Enter" && inputTime === document.activeElement) {
        timeFormArea.innerHTML = inputTime.value;
    }
});

appendCategoryButton.addEventListener("click", () => {
    var text = document.forms.categoryText.inputText.value;
    var option = document.createElement("option");
    option.text = text;
    categoryPullDown.appendChild(option);
    document.forms.categoryText.inputText.value = "";
});

appendButton.addEventListener("click", () => {
    contents.insertAdjacentHTML("beforeend", addition);
});

calculateButton.addEventListener("click", () => {
    line.classList.replace("active", "passive");
    let sum = 0;
    for(let i=0; i<time.length; i++) {
        sum += parseInt(time.item(i).value);
    }
    title.innerHTML = 100 - sum + "<span>時間<\span>";
    line.style.strokeDashoffset = 440 - (440 * (100 - sum)) / 100;
    setTimeout(() => {
        line.classList.replace("passive", "active");
    }, 300);
});