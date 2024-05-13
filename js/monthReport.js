const line = document.getElementById("line");
const title = document.getElementById("title");
const timeFormArea = document.getElementById("time_form_area");
const maxTime = document.getElementById("max_time");
const load = document.getElementById("load");
const save = document.getElementById("save");
const contents = document.getElementById("contents");
const inputTable = document.getElementById("inputTable");
const graphArea = document.getElementById("graphArea");
const time = document.getElementsByClassName("time");
const analyticsTable = document.getElementById("analytics-table");
const appendButton = document.getElementById("appendButton");
const calculateButton = document.getElementById("calculateButton");
const pdfButton = document.getElementById("pdf-button");
const popupWrapper = document.getElementById("popup-wrapper");
const close = document.getElementById("close");
const memo = document.getElementById("memo");

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
        timeFormArea.innerHTML = parseFloat(inputTime.value);
        MAX_TIME = parseFloat(inputTime.value);
        maxTime.value = MAX_TIME;
        calculate();
        inputTime.remove();
    }
});

function deleteRow (num) {
    var result = window.confirm("本当に削除しますか？");
    if (result) {
        for (let i=num; i<categoryNum; i++) {
            document.getElementById("date" + num).value = document.getElementById("date" + (num + 1)).value;
            document.getElementById("category" + num).value = document.getElementById("category" + (num + 1)).value;
            document.getElementById("detail" + num).value = document.getElementById("detail" + (num + 1)).value;
            document.getElementById("time" + num).value = document.getElementById("time" + (num + 1)).value;
            document.getElementById("deadline" + num).value = document.getElementById("deadline" + (num + 1)).value;
            document.getElementById("manager" + num).value = document.getElementById("manager" + (num + 1)).value;
            document.getElementById("date" + num).value = document.getElementById("date" + (num + 1)).value;
            document.getElementById("status" + num).value = document.getElementById("status" + (num + 1)).value;
        }
        document.getElementById("inputTabletr" + categoryNum).remove();
        categoryNum--;
        return true;
    } else {
        return false;
    }
}

function append() {
    categoryNum++;
    inputTable.insertAdjacentHTML("beforeend", `
    <tr id="inputTabletr${categoryNum}">
        <td><input type="text" class="date" id="date${categoryNum}" name="date${categoryNum}" form="save"></td>
        <td><input type="text" class="category" id="category${categoryNum}" name="category${categoryNum}" form="save"/></td>
        <td><textarea class="detail" id="detail${categoryNum}" name="detail${categoryNum}" form="save"></textarea></td>
        <td><input type="text" class="time" id="time${categoryNum}" name="time${categoryNum}" value="0" form="save"/></td>
        <td><input type="date" class="deadline" id="deadline${categoryNum}" name="deadline${categoryNum}" form="save"></td>
        <td><input type="text" class="manager" id="manager${categoryNum}" name="manager${categoryNum}" form="save"></td>
        <td><input type="text" class="status" id="status${categoryNum}" name="status${categoryNum}" form="save"></td>
        <td><button id=delete-row-btn${categoryNum}" onclick="return deleteRow(${categoryNum})">削除</button></td>
    </tr>
    `);
}

function calculate() {
    line.classList.replace("active", "passive");
    let sum = 0;
    for(let i=0; i<time.length; i++) {
        sum += parseFloat(time.item(i).value);
    }
    timeFormArea.innerHTML = MAX_TIME - sum;
    line.style.strokeDashoffset = 440 - (440 * (MAX_TIME - (MAX_TIME - parseFloat(timeFormArea.innerHTML)))) / MAX_TIME;
    setTimeout(() => {
        line.classList.replace("passive", "active");
    }, 300);
}

function inputForm (i, row_data) {
    document.getElementById("date" + i).value = row_data[3];
    document.getElementById("category" + i).value = row_data[4];
    document.getElementById("detail" + i).value = row_data[5];
    document.getElementById("time" + i).value = row_data[6];
    document.getElementById("deadline" + i).value = row_data[7];
    document.getElementById("manager" + i).value = row_data[8];
    document.getElementById("status" + i).value = row_data[9];
}

function addAnalytics () {
    analyticsTable.insertAdjacentHTML("beforeend", `
        <tr><td><p>アナリティクス${analyticsNum + 1}：</p></td></tr>
        <tr><td><p class="smallText">資料添付</p></td></tr>
        <tr><td><p id="analytics-file-select${analyticsNum}"></p></td></tr>
        <tr><td><input type="file" id="analyticsFile${analyticsNum}" name="analyticsFile${analyticsNum}" form="save"></td></tr>
        <tr><td><pre><textarea class="analytics" id="analytics${analyticsNum}" name="analytics${analyticsNum}" form="save"></textarea></pre></td></tr>
    `);
    analyticsNum++;
}

pdfButton.addEventListener('click', () => {
    var result = window.confirm("保存しますか？");
    if (result) {
        document.getElementById("popup").value = "true";
        document.getElementById("save").submit();
        return true;
    } else {
        return false;
    }
});

popupWrapper.addEventListener('click', e => {
    if (e.target.id === popupWrapper.id || e.target.id === close.id) {
        popupWrapper.style.display = "none";
    }
});