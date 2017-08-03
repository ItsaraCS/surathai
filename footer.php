        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(e) {
            //--Variable
            var factory = new Factory();
            var ajaxUrl = 'API/userAPI.php';
            var params = {};

            //--Page load
            setInit();

            //--Function
            function setInit() {
                factory.initService.setMenu();
                factory.utilityService.getDataImportant();
                
                $.datepicker.regional['th'] = { //--Datepicker
                    dateFormat: 'dd/mm/yy',
                    changeMonth: true,
                    changeYear: true,
                    yearOffSet: 543
                };
                $.datepicker.setDefaults($.datepicker.regional['th']);
                $('.datepicker').datepicker($.datepicker.regional['th']);
                $('.datepicker').datepicker('setDate', new Date());
            }

            //--Event
            $(document).on('click', '.datepicker-btn', function(e){
                e.preventDefault();

                $(this).closest('.input-group').find('input').focus();
            });

            $(document).on('click', '.user-menu', function(e) {
                e.stopPropagation();

                $('.user-menu-detail').toggleClass('show');
            });

            $(window).click(function(e) {
                if($('.user-menu-detail').hasClass('show')) 
                    $('.user-menu-detail').toggleClass('show');
            });

            $(document).on('click', '#loginBtn', function(e) {
                e.preventDefault();

                params = {
                    fn: 'login',
                    user: $('form[name="loginForm"] #username').val() || '',
                    password: $('form[name="loginForm"] #password').val() || ''
                };
                
                factory.connectDBService.sendJSONObj(ajaxUrl, params, false).done(function(res) {
                    if(res != undefined) {
                        var data = JSON.parse(res);
                        
                        if(data.id == 0) {
                            factory.utilityService.getPopup({
                                infoMsg: 'บัญชีผู้ใช้ไม่ถูกต้อง',
                                btnMsg: 'ปิด'
                            });

                            $('form[name="loginForm"] #username, ' +
                                'form[name="loginForm"] #password').val('');
                        } else {
                            sessionStorage.setItem('userID', data.id);
                            window.open('map.php', '_self');
                        }
                    }
                });
            });

            $(document).on('click', '#logoutBtn', function(e) {
                e.preventDefault();

                factory.connectDBService.sendJSONObj(ajaxUrl, { fn: 'logout' }, false).done(function(res) {
                    if(res != undefined){
                        var data = JSON.parse(res);
                        
                        if(data.id == 0) {
                            sessionStorage.removeItem('userID');
                            window.open('login.php', '_self');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>