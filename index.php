<html lang="ja">
<meta charset="UTF-8" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="css/bootstrap/bootstrap-5.2.3.min.css">
<link rel="stylesheet" href="css/app.css">
<link rel="stylesheet" href="css/branch.css">

<body>
<script type="text/javascript">
$(function() {
    $('#send-form').submit(function() {
        $(document).ajaxSend(function() {
            $('#overlay').fadeIn(300);
        });

        for (let i = 0; i < 9; i++) {
            $("#dn" + i).text("-");
            $("#sts" + i).text("-");
        }

        $("#out1").text("");
        $("#out2").text("");

        let fd = new FormData($('#send-form').get(0));
        let entry = "";
        for (entry of fd.entries()) {}

        if (entry[1].length < 20) {
            alert(entry[1] + "(" + entry[1].length + ") \n２０文字以上入力してください。");
            return false;
        }

        let startTime = Date.now();
        $.ajax({
            type: 'POST',
            url: 'createname.php',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'html'
        }).done(function(data) {
            let exectime = (Date.now() - startTime) / 1000;
            $("#out1").text(exectime.toFixed(2) + 'sec');
            $('#overlay').fadeOut(300);

            var domain_name = JSON.parse(data);
            var get_parm = "";

            for (let i = 0; i < domain_name.data.length - 1; i++) {
                $("#dn" + i).text(domain_name.data[i]);
                get_parm += domain_name.data[i] + ',';
            }

            get_parm = get_parm.slice(0, -1);

            startTime = Date.now();
            $.ajax({
                type: 'GET',
                url: 'c13-9-1.php',
                data: {
                    domainnames: get_parm
                },
                dataType: 'html'
            }).done(function(data) {
                exectime = (Date.now() - startTime) / 1000;
                $("#out2").text(exectime.toFixed(2) + 'sec');
                $('#overlay').fadeOut(300);

                var domain_sts = JSON.parse(data);

                for (let i = 0; i < domain_sts.results.length; i++) {
                    $("#sts" + i).text(domain_sts.results[i].status);
                }
            }).fail(function(XMLHttpRequest, status, e) {
                alert(e);
            });
        }).fail(function(XMLHttpRequest, status, e) {
            alert(e);
        });

        return false;
    });
});
</script>

<header class="container-fluid sticky-top">
    <div class="header">
        <table class="w-100">
            <tr>
                <td>
                    <div>
                        <span class="logic-plan">ChatGPT - Value Domain Domain Search テスト用</span>
                    </div>
                </td>
                <td class="text-end text-nowrap">
                </td>
            </tr>
        </table>
    </div>
</header>

<div class="container screen_size mx-auto pt-4">

    <form id="send-form" method="post">
        生成したいドメイン名のコンセプトを入力してください<br>
        <div style="max-width: 600;" class="text-end mx-auto">
            <textarea id="post_text" name="post_text" placeholder="例）ECサイトで着物を扱います。ワールドワイドに売るサイトです。青色を強調してください。" class="form-control string_counter goal" style="height: 110px;"></textarea>
        </div><br>
        <input type="submit" class="btn btn-gold w-100" value="作成する" />
    </form>
    <br>
    <legend class="serif text-center" style="font-size: 100%;">Domain Name Generator</legend>
    <table class="table table-sm table-striped table-bordered mb-0 mt-2">
        <thead class="text-center bg-white">
            <tr height="16">
                <th width="16">No</th>
                <th width="150">domain</th>
                <th>status</th>
            </tr>
        </thead>
        <?php
        for ($i = 0; $i < 9; $i++) {
            print '<tr><td class="text-center">' . ($i + 1) . '</td><td><div id="dn' . $i . '"></div></td><td><div id="sts' . $i . '"></div></td></tr>' . "\n";
        }
        ?>

        <thead class="text-center bg-white">
            <tr height="16">
                <td width="16"></td>
                <td width="150"><div id="out1"></div></td>
                <td><div id="out2"></div></td>
            </tr>
        </thead>
    </table>

</div>

<footer class="container-fluid">
    <div class="copyright">
        2023 PicoLix Design All Rights Reserved.
    </div>
</footer>


<div id="overlay"><div class="cv-spinner"><span class="spinner"></span></div></div>
</body>
</html>
