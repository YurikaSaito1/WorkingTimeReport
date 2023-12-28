var dbaddition = `
<div class="graphArea">
<div class="date">
    <p>日付</p>
    <input type="date" value=<?= $dbdate[1] ?>>
    </div>
<div class="category">
    <select id="categoryPullDown` + categoryNum + `" name="category">
        <option><?= $dbcategory[2] ?></option>
    </select>
</div>
<div class="input">
    <input type="text" class="time" id="time` + categoryNum + `" name="time" value=<?= $dbtime[$_POST['count']] ?>
    <p>時間</p>
</div>
`;
let i = '<?php echo $i; ?>';
document.getElementById("number").innerHTML = i;
let j = 1;
for (let j=1; j<i; j++) {
    categoryNum++;
    contents.insertAdjacentHTML("beforeend", dbaddition);
    document.frmRegist.count.value = j;
    document.frmRegist.submit();
}