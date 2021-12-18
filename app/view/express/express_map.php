<html>
    <body style="background: honeydew; padding:0 15px;">
    <script type="text/javascript" src="/static/front/js/jquery-3.2.1.min.js"></script>
        <div class=" cart-bg">
  <div class="order-box">
   	  <div class="col-m5 float-l">
        	<div class="order-boxw">
            	<div class="order-box-tit">門市資訊</div>
                <div class="order-box-table">
                <table width="100%" border="1" cellpadding="2" cellspacing="0">
                  <tbody>
                    <tr>
                <td width="120">超商門市編號</td>
                <td><?php echo $CVSStoreID;?></td>
            </tr>
            <tr>
                <td width="120">超商門市名</td>
                <td><?php echo $CVSStoreName;?></td>
            </tr>
            <tr>
                <td width="120">超商門市地址</td>
                <td><?php echo $CVSAddress;?></td>
            </tr>
            <tr>
                <td width="120">超商門市電話</td>
                <td><?php echo $CVSTelephone;?></td>
            </tr>
            <tr>
                <td width="120">&nbsp;</td>
                <td><div class="order-btn"><a href="javascript:void();"  onclick="CallStoreAddress()" style="background: #333;
    color: #fff;
    width: 120px;
    padding: 5px 10px;
    font-size: 18px;
    line-height: 30px;
    display: inline-block;
    text-align: center;
    border: 2px solid #333;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;" class="Collection-btn">確認回傳</a> </div></td>
            </tr>
                  </tbody>
                </table>
              </div>
        </div>
 
              </div>

 
           

           
  </div>
  </div>
        <script type="text/javascript">
            function CallStoreAddress(){
                    window.opener=null;
                    window.open('','_self');
                    window.close();
            }
            $(function() {
                var data = {
                        CVSStoreID: '<?php echo $CVSStoreID;?>',
                        CVSStoreName: '<?php echo $CVSStoreName;?>',
                        CVSAddress: '<?php echo $CVSAddress;?>',
                        CVSTelephone: '<?php echo $CVSTelephone;?>'
                    };
                    localStorage.setItem('csv_data', JSON.stringify(data));
            });
        </script>
    </body>
</html>
