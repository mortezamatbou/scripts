pm.sendRequest(pm.environment.get('BASE_URL') + "/captcha", function (err, response) {
    console.log(response.json());
    pm.environment.set('CAPTCHA_KEY', response.json().body.key);
    pm.environment.set('CAPTCHA_VALUE', response.json().body.val);
});

// body of request
body_of_request = {
    "otpKey": "{{CAPTCHA_KEY}}",
    "otpValue": "{{CAPTCHA_VALUE}}"
}


pm.test("Set environment token", function() {
    pm.response.to.have.status(200);
    const result = pm.response.json();
    pm.environment.set('TOKEN', result.body.token);
});

