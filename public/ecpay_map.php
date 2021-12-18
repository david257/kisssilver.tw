<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="/static/front/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="/static/js/layer/layer.js"></script>
</head>
<body style="background: honeydew; padding:0 15px;">
    
	<?php $data = $_POST; ?>
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
                <td><?php echo $data['CVSStoreID'];?></td>
            </tr>
            <tr>
                <td width="120">超商門市名</td>
                <td><?php echo $data['CVSStoreName'];?></td>
            </tr>
            <tr>
                <td width="120">超商門市地址</td>
                <td><?php echo $data['CVSAddress'];?></td>
            </tr>
            <tr>
                <td width="120">超商門市電話</td>
                <td><?php echo $data['CVSTelephone'];?></td>
            </tr>
            <tr>
                <td width="120">&nbsp;</td><!--onclick="CallStoreAddress()"-->
                <td><div class="order-btn"><a href="javascript:void(0);" style="background: #333;
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

			function IsPC() {
				var userAgentInfo = navigator.userAgent;
				var Agents = ["Android", "iPhone","SymbianOS", "Windows Phone","iPad", "iPod"];
				var flag = true;
				for (var v = 0; v < Agents.length; v++) {
					if (userAgentInfo.indexOf(Agents[v]) > 0) {
						flag = false;
						break;
					}
				}
				return flag;
			}

			function isLocalStorageSupported() {
				var testKey = 'test',
					storage = window.sessionStorage;
				try {
					storage.setItem(testKey, 'testValue');
					storage.removeItem(testKey);
					return true;
				} catch (error) {
					return false;
				}
			}

            $(function() {
                var data = {
                        CVSStoreID: "<?php echo $data['CVSStoreID'];?>",
                        CVSStoreName: "<?php echo $data['CVSStoreName'];?>",
                        CVSAddress: "<?php echo $data['CVSAddress'];?>",
                        CVSTelephone: "<?php echo $data['CVSTelephone'];?>"
                    };
                    localStorage.setItem('csv_data', JSON.stringify(data));
					

					if(!isLocalStorageSupported()) {
						alert('瀏覽器不支持本地儲存');
					}

					$(".order-btn").click(function() {
					    if(IsPC()) {
                            closeWin();
                        } else {
                            document.location.href = '/Checkout/index?ref=ecpay_map';
                        }
                    })

					if(IsPC()) {
                        closeWin();
					} else {
						document.location.href = '/Checkout/index?ref=ecpay_map';
					}
					/*setInterval(function(){
						if(timeS<=0) {
							window.close();
						}
						$(".Collection-btn").text("確認回傳("+timeS+")")
						timeS--;
					}, 1000);*/
            });

            function closeWin() {
                localStorage.setItem('close_command', 'close');
            }
        </script>
    </body>
</html>