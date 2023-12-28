var dbaddition = `
<div class="graphArea">
<div class="date">
    <p>日付</p>
    <input type="date" value=<?= $dbdate[` + j + `] ?>>
    </div>
<div class="category">
    <select id="categoryPullDown` + categoryNum + `" name="category" value=<?= $dbcategory[` + j + `]?>>
        <option>動画編集</option>
        <option>Web構築</option>
    </select>
</div>
<div class="input">
    <input type="text" class="time" id="time` + categoryNum + `" name="time" value=<?= $dbtime[` + j + `]?>>
    <p>時間</p>
</div>
`;
let i = '<?php echo $i; ?>';
document.getElementById("number").innerHTML = "aaa";
for (let j=0; j<i; j++) {
    categoryNum++;
    contents.insertAdjacentHTML("beforeend", dbaddition);
}