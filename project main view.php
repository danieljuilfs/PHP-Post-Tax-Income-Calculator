<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Tax Calculator</title>
    <script src="https://unpkg.com/htmx.org@1.9.11"></script>
    <link rel="stylesheet" href="project.css">
</head>
<body>
    <h1>Post-tax Income Calculator</h1>
    
    <div class="input">
        <form method="post" hx-post="controller.php" hx-target="#output">
            <div class="inlineme">
                <label for="paytype">Select your pay type</label><br>
                <select name="paytype" id="payperiod" hx-trigger="change" hx-post="hourly.php" hx-target="#method">
                    <option>Salary</option>
                    <option>Hourly</option>
                </select>
            </div>    
            <div id="method" class="inlineme">
                <label for="interval">Salary Interval</label><br>
                <select name="interval" id="paymethod">
                    <option>Per Year</option>
                    <option>Per Pay Period </option>
            </select>
            </div>
            <div class="inlineme">
                <label for="amount">Amount </label><br>
                <input type="text" id="amount" name="amount" value="0">
            </div>
            <br><br>
            <div class="inlineme">
            <label for="payperiod">Select Pay period</label><br>
                <select name="payperiod" id="payperiod">
                    <option>Weekly</option>
                    <option>Bi-Weekly</option>
                    <option>Monthly</option>
                    <option>Semi-Monthly</option>
                </select>
            </div>
            <div class="inlineme">
        
                <label for="filingstatus">Select filing status</label><br>
                <select name="filingstatus" id="filingstatus" hx-trigger="change" hx-post="marriage.php" hx-target="#filing">
                    <option>Single</option>
                    <option>Married filing jointly</option>
                    <option>Married filing separately</option>
                    <option>Head of household</option>
                </select>
            </div>
            <div class="inlineme" id="filing"></div><br><br>
            <div class="inlineme">
                <label for="states">Select state</label><br>
                <select name="states" id="states">
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA">Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                </select>
            </div><br><br>
            <div class="inlineme">
                <label for="insurance">Insurance Deductions</label><br>
                <input type="text" id="insurance" name="insurance" value=0>
            </div>
            <div class="inlineme">
                <label for="pretax">Pretax Deductions</label><br>
                <input type="text" id="pretax" name="pretax" value=0>
            </div>
            <div class="inlineme">
                <label for="additional">Additional Withholding</label><br>
                <input type="text" id="additional" name="additional" value=0>
            </div><br><br>
            <input type="submit" name='submit' value='Submit'>
        </form>
    </div>
    <div class="output" id="output">
        <div class="earnings outputme">
            <span class="purptext">$0</span>
            <p><b>Earnings</b></p><br>
        </div>
        <div class="taxes outputme">
            <span class="purptext">$0</span>
            <p><b>Taxes</b></p><br>
        </div>
        <div class="deductions outputme">
            <span class="purptext">$0</span>
            <p><b>Deductions</b></p><br>
        </div>
        <div class="takehome outputme">
            <span class="purptext">$0</span>
            <p><b>Take Home</b></p><br>
        </div>
    </div>

</body>
</html>