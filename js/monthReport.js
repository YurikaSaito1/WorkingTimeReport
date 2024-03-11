const line = document.getElementById("line");
const title = document.getElementById("title");
const timeFormArea = document.getElementById("time_form_area");
const load = document.getElementById("load");
const save = document.getElementById("save");
const contents = document.getElementById("contents");
const inputTable = document.getElementById("inputTable");
const graphArea = document.getElementById("graphArea");
const time = document.getElementsByClassName("time");
const appendButton = document.getElementById("appendButton");
const calculateButton = document.getElementById("calculateButton");
const memo = document.getElementById("memo");

var MAX_TIME = Number(timeFormArea.innerHTML);

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

function append() {
    categoryNum++;
    inputTable.insertAdjacentHTML("beforeend", `
    <tr id="inputTabletr${categoryNum}">
        <td><input type="text" class="date" id="date${categoryNum}" name="date${categoryNum}"></td>
        <td><input type="text" class="category" id="category${categoryNum}" name="category${categoryNum}"/></td>
        <td><textarea class="detail" id="detail${categoryNum}" name="detail${categoryNum}"></textarea></td>
        <td><input type="text" class="time" id="time${categoryNum}" name="time${categoryNum}"/></td>
        <td><input type="date" class="deadline" id="deadline${categoryNum}" name="deadline${categoryNum}"></td>
        <td><input type="text" class="manager" id="manager${categoryNum}" name="manager${categoryNum}"></td>
        <td><input type="text" class="status" id="status${categoryNum}" name="status${categoryNum}"></td>
        <td><button onclick="deleteRow(${categoryNum})">削除</button></td>
    </tr>
    `);
}

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

function inputForm (i, row_data) {
    document.getElementById("date" + i).value = row_data[3];
    document.getElementById("category" + i).value = row_data[4];
    document.getElementById("detail" + i).value = row_data[5];
    document.getElementById("time" + i).value = row_data[6];
    document.getElementById("deadline" + i).value = row_data[7];
    document.getElementById("manager" + i).value = row_data[8];
    document.getElementById("status" + i).value = row_data[9];
}

function deleteRow (num) {
    document.getElementById("inputTabletr" + num).remove();
}