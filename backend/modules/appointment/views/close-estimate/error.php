<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
        <head>
                <title></title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link href="backend/web/css/bootstrap.css" rel="stylesheet" type="text/css" />
                <style>
                        .eroors{
                                background-color: Red;
                                height: auto;
                                text-align: center;
                                font-size: 30px;
                        }
                </style>
        </head>
        <body style="background-color: graytext;">
                <div class="col-lg-12 eroors">
                        <?php
                        echo $error;
                        ?>
                </div>
                <div class="table-responsive">

<!--                        <table class="table table-bordered">
                                <caption style="text-align: center;color: black;font-size: 30px;">ERROR CODES</caption>
                                <tr>
                                        <th style="text-align: center;"><b>#</b></th>
                                        <th><b>Eroor Code</b></th>
                                        <th><b>Description</b></th>
                                </tr>
                                <tr>
                                        <td>1</td>
                                        <td>1001</td>
                                        <td>Checking Appointment is present in Port-Call-Data Controller</td>
                                </tr>
                        </table>-->
                </div>
        </body>
</html>
