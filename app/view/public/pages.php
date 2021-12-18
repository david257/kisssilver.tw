<nav aria-label="Page navigation">
    <ul class="pager">
        <?php
        if($nowPage>1) {
            $params["page"] = $nowPage-1;
            $gotoUrl = front_link($url, $params);
        ?>
        <li class="previous pull-left"><a href="{:$gotoUrl}">上一頁</a></li>
        <?php } else { ?>
        <li class="previous disabled pull-left"><a href="#">上一頁</a></li>
        <?php } ?>
        <li>第<select onchange="gotoPage(this.value)" class="form-control">
                <?php
                for($page = 1; $page<=$totalPages; $page++) {
                    $params["page"] = $page;
                    $gotoUrl = front_link($url, $params);
                    if($page == $nowPage) {
                        $selected = 'selected';
                    } else {
                        $selected  ='';
                    }
                ?>
                <option {:$selected} value="{:$gotoUrl}">{$page}</option>
                <?php } ?>
            </select>頁</li>
        <?php
        if($nowPage<$totalPages) {
            $params["page"] = $nowPage+1;
            $gotoUrl = front_link($url, $params);
        ?>
        <li class="next pull-right"><a href="{:$gotoUrl}">下一頁</a></li>
        <?php } else { ?>
        <li class="next disabled pull-right"><a href="#">下一頁</a></li>
        <?php } ?>
    </ul>
</nav>
<script>
    function gotoPage(url) {
        document.location.href = url;
    }
</script>