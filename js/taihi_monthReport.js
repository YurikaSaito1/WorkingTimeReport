const line = document.getElementById("line");
const title = document.getElementById("title");
const timeFormArea = document.getElementById("time_form_area");
const load = document.getElementById("load");
const save = document.getElementById("save");
const selectedFile = document.getElementById("selectedFile");
const appendCategory = document.getElementById("appendCategory");
const appendCategoryButton = document.getElementById("appendCategoryButton");
const contents = document.getElementById("contents");
const graphArea = document.getElementById("graphArea");
const time = document.getElementsByClassName("time");
const appendButton = document.getElementById("appendButton");
const calculateButton = document.getElementById("calculateButton");
const memo = document.getElementById("memo");

var MAX_TIME = Number(timeFormArea.innerHTML);
var categoryNum = 0;

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
        MAX_TIME = Number(inputTime.value);
        inputTime.remove();
    }
});

selectedFile.addEventListener("change", (event) => {
    var files = selectedFile.files;
    var f = files[0];
    var reader = new FileReader();
    reader.readAsText(f);
    reader.onload = function() {
        let array = reader.result.split(",");
        workInput(array);
    }
});

function workInput(array) {
    const category = document.getElementById("categoryPullDown" + categoryNum);
    let options = category.options;
    let i = 0;
    for (let option of options) {
        if (option.value == array[1]) {
            category.value = array[1];
            break;
        }
        i++;
        if (options.length == i) {
            var newOption = new Option();
            newOption.text = array[1];
            category.appendChild(newOption);
            category.value = array[1];
        }
    }
    const time = document.getElementById("time" + categoryNum);
    time.value = array[2];
}

appendCategoryButton.addEventListener("click", () => {
    var text = document.forms.categoryText.inputText.value;
    var option = [];
    let i = 0;
    while(document.getElementById("categoryPullDown" + i) != null) {
        option[i] = document.createElement("option");
        option[i].text = text;
        i++;
    }
    i = 0;
    while(document.getElementById("categoryPullDown" + i) != null) {
        document.getElementById("categoryPullDown" + i).appendChild(option[i]);
        i++;
    }
    document.forms.categoryText.inputText.value = "";
});

appendButton.addEventListener("click", () => {
    categoryNum++;
    graphArea.insertAdjacentHTML("beforeend", `
    <div class="formArea">
        <div class="date">
            <p>日付</p>
            <input type="date" name="date${categoryNum}">
        </div>
        <div class="category">
            <select id="categoryPullDown${categoryNum}" name="categoryPullDown${categoryNum}">
            </select>
        </div>
        <div class="input">
            <p>URL:</p>
            <input type="text" class="url" id="url${categoryNum}" name="url${categoryNum}"/>
        </div>
        <div class="input">
            <input type="text" class="time" id="time${categoryNum}" name="time${categoryNum}"/>
            <p>時間</p>
        </div>
    </div>
    `);
    const categoryPullDown0 = document.getElementById("categoryPullDown0");
    let str = [];
    for (let i = 0; i < categoryPullDown0.length; i++) {
        str[i] = categoryPullDown0.options[i];
    }
    for (let i = 0; i < categoryPullDown0.length; i++) {
        let option = document.createElement('option');
        option.textContent = str[i].value;
        document.getElementById("categoryPullDown" + categoryNum).appendChild(option);
    }
});

calculateButton.addEventListener("click", () => {
    line.classList.replace("active", "passive");
    let sum = 0;
    for(let i=0; i<time.length; i++) {
        sum += parseInt(time.item(i).value);
    }
    timeFormArea.innerHTML = Number(timeFormArea.innerHTML) - sum;
    line.style.strokeDashoffset = 440 - (440 * (MAX_TIME - (MAX_TIME - Number(timeFormArea.innerHTML)))) / MAX_TIME;
    setTimeout(() => {
        line.classList.replace("passive", "active");
    }, 300);
});