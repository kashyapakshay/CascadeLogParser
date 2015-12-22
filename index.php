<html>
    <head>
        <script src="https://code.jquery.com/jquery-2.1.3.min.js" type="text/javascript"></script>
        <script>
            $(document).ready(function() {
                var htmlString = "";
                var cascadeDomain = "https://cascade.union.edu:8443/";
                var prefix = cascadeDomain + "entity/open.act?id=";
                var assetLink = "";
                
                $.getJSON("cascade.json", function(result) {
                    $.each(result.logContent, function(i, field) {
                        if(field.id != "None" && field.id != "") {
                            assetLink = prefix + field.id + "&type=" + field.aType;
                            linkString = "
            <a href='"+ assetLink +"' target='_blank'>" + field.id + "</a>";
                        }
                        else
                            linkString = field.id;
                        
                        htmlString = 
                        "
            <div class='block f-"+ field.type +"'> \
                            
                <div class='expand' onclick='toggleMessageDisplay(this); return false;'> \
                                
                    <img src='assets/images/expand.png' /> \
                            
                </div> \
                            \
                            
                <span class='date'>" + field.date + "</span> \
                            
                <span class='time'>" + field.time + "</span> \
                            
                <span class='type "+ field.type +"'>" + field.type + "</span> \
                            
                <span class='exception'>" + field.exception + "</span> \
                            
                <span class='user'> User: " + field.user + "</span> \
                            \
                            
                <span class='id'> ID: \
                                 "+ linkString +" \
                            </span> \
                            \
                            
                <span class='aType'> Type: " + field.aType + "</span> \
                            
                <br /> \
                            
                <p class='message' style='display: none'>" + field.message + "</p> \
                        
            </div>";
                        
                        $("#log-wrapper").append(htmlString);
                    });
                });
                
                $('#filter').on('change', function() {
                    var type =  $(this).val();
                    $('.block').hide();
                    
                    if(type == "all") {
                        $('.block').show();
                    }
                    else {
                        
                        $('.f-'+ type).siblings().hide();
                        $('.f-' + type).show();
                    }
                });
            });
            
            // Expand and Hide message.
            // Can be implemented with JQuery too.
            function toggleMessageDisplay(d) {
                var parent = d.parentNode;
                
                if(parent.getElementsByClassName('message')[0]
                    .getAttributeNode("style").value == 'display: none')
                    
                    parent.getElementsByClassName('message')[0]
                        .getAttributeNode("style").value = "display: block";
                else
                    parent.getElementsByClassName('message')[0]
                        .getAttributeNode("style").value = "display: none";
            }
        
        </script>
        <style>
            body {
                font-family: Helvetica, sans-serif;
            }
    
            #filter-c {
                margin: 25px 0 0 47px;
            }
            
            .block {
                margin: 15px 0 0 20px;
                
                width: auto;
                height: auto;
            }
    
            .expand {
                float: left;
                margin: 6px 0 0 5px;
                width: 16px;
                height: 16px;
            }
            
            /* Sections of the Message Block */
            .date {
                padding: 5px;
                padding-right: 4px;
                margin-left: 5px;
                border-right: 1px dotted #fff;
                color: #fff;
                background-color: #4eb849;
                float: left;
            }
            
            .time {
                padding: 5px;
                color: #fff;
                background-color: #4eb849;
                float: left;
            }
            
            .type {
                padding: 5px;
                color: #fff;
                float: left;
            }
            
            /* Types of messages and styles for each */
            .INFO {
                color: #4386c6;
                background-color: #fff;
                height: 16px;
                
                border-top: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
            }
            
            .DEBUG {
                color: #db2623;
                background-color: #fff;
                height: 16px;
                
                border-top: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
            }
            
            .WARN {
                background-color: #f8ca09;
            }
            
            .ERROR {
                background-color: #db2623;   
            }
            
            .FATAL {
                background-color: #ff6600;
                
                text-decoration: underline;
                font-weight: 800;
            }
            
            .exception {
                padding: 5px;
                color: #fff;
                background-color: #4386c6;
                float: left;
            }
            
            .user, .id, .aType {
                padding: 5px;
                color: #f9f9f9;
                background-color: #333;
                border-right: 1px dotted #f9f9f9;
                float: left;
            }
            
            .id a {
                color: inherit;
            }
            
            .message {
                padding: 5px;
                color: #333;
                margin: 10px 0 0 26px;
                text-align: left;
                border: 1px dotted #ccc;
                background-color: #eee;
            }
        </style>
    </head>
    <body>
        <span style='margin-left: 45px; margin-top: 15px; font-size: 25px;'>Cascade Log</span>
        <div id="filter-c">
            Filter:
            
            <select id="filter">
                <option value="all">-- ALL --</option>
                <option value="INFO">INFO</option>
                <option value="WARN">WARN</option>
                <option value="DEBUG">DEBUG</option>
                <option value="ERROR">ERROR</option>
                <option value="FATAL">FATAL</option>
            </select>
        </div>
        <div id='log-wrapper'></div>
    </body>
</html>