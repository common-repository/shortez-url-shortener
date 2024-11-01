<?php
if (isset($_REQUEST['action']) && is_callable([shortez::instance(), $_REQUEST['action']]))
	shortez::instance()->{$_REQUEST['action']}();

$data = array_merge([
	'token' => '',
	'domains' => '',
	'patterns' => ''
], (array)json_decode(@get_option('shortez') ?: '', true));
?>
<style>
	#shortez-template {
		margin: 20px 20px 20px 0;
		background: white;
		padding: 30px;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;
		border: 1px solid #ddd;
	}

	#shortez-template h1 {
		margin-top: 0;
	}

	#shortez-template .w100 {
		width: 100%;
	}

	#shortez-template .notice {
		line-height: 40px;
		margin: 0;
	}
</style>
<div id="shortez-template">
	<h1>Configure shortez</h1>

	<?php if(isset($_POST['action'])) : ?>
		<p class="notice notice-success">
			Save data successfully
		</p>
	<?php endif; ?>

	<form id="shortez-form" method="POST" action="<?= $_SERVER['REQUEST_URI']; ?>">
		<input type="hidden" name="action" value="saveData">
		<table class="form-table">
			<tbody>
			<tr>
				<th>
					<label for="shortez-template-token">Token key: Important</label><br>
					<a href="https://shortez.com/member/tools/quick">Find Your Token</a>
				</th>
				<td>
					<input type="text" id="shortez-template-token" autocomplete="off"
					       class="w100" name="token" value="<?= $data['token']; ?>">
				</td>
			</tr>
			<tr>
				<th>
					<label for="shortez-template-domains">Domain: Important</label>
					<p>Put the links here that you do not want to shorten</p>
					<p>यहां पर उन लिंक को डाले जिनको आप शॉर्ट नहीं करना चाहते हैं</p>
					<p>For example:<br/>
					<code>yourwebsite.com</code>
					<p><em>Each domain writes 1 line</em></p>
				</th>
				<td>
					<textarea type="text" id="shortez-template-domains" rows="5"
					          class="w100" name="domains"><?= $data['domains']; ?></textarea>
				</td>
			</tr>
			<tr>
				<th>
					<label for="shortez-template-patterns">Patterns: Advance Users Only (Optional) </label>
					<p>Encrypts all paths by regular expression. Use spaces to separate the expression and the modifier</p>
					<p>For example: <br/>
					<code>link</code><br/>
                    <code>^link$ i</code>
					</p>
					<p><em>Each expression writes 1 line</em></p>
				</th>
				<td>
					<textarea type="text" id="shortez-template-patterns" rows="5"
					          class="w100" name="patterns"><?= $data['patterns']; ?></textarea>
				</td>
			</tr>
			</tbody>
		</table>

		<button class="button button-primary" type="submit">Save</button>
	</form>
</div>


<script>

    var shortez_url = 'https://shortez.com/';
    var shortez_api_token = '1790d27366f514c8f0823dbb3bcadd2079a148c7';
    var shortez_advert = 2;
    var shortez_domains = ['userscloud.com', 'drive.google.com', 'docs.google.com', 'www.mediafire.com', 'zippyshare.com', 'uploadocean.com', 'openload.co'];

    function shortez_get_url(e) {
        var n = document.createElement("a");
        return n.href = e, n
    }

    function shortez_get_host_name(e) {
        var n;
        return void 0 === e || null === e || "" === e || e.match(/^\#/) ? "" : -1 === (e = shortez_get_url(e)).href.search(/^http[s]?:\/\//) ? "" : (n = e.href.split("/")[2], (n = n.split(":")[0]).toLowerCase())
    }

    function shortez_base64_encode(e) {
        return btoa(encodeURIComponent(e).replace(/%([0-9A-F]{2})/g, function(e, n) {
            return String.fromCharCode("0x" + n)
        }))
    }
    document.addEventListener("DOMContentLoaded", function(e) {
        if ("undefined" != typeof shortez_url && "undefined" != typeof shortez_api_token) {
            var n = 1;
            "undefined" != typeof shortez_advert && (2 == shortez_advert && (n = 2), 0 == shortez_advert && (n = 0));
            var l = document.getElementsByTagName("a");
            if ("undefined" == typeof shortez_domains)
                if ("undefined" == typeof shortez_exclude_domains);
                else
                    for (o = 0; o < l.length; o++) {
                        var t = shortez_get_host_name(l[o].getAttribute("href"));
                        t.length > 0 && -1 === shortez_exclude_domains.indexOf(t) ? l[o].href = shortez_url + "full/?api=" + encodeURIComponent(shortez_api_token) + "&url=" + shortez_base64_encode(l[o].href) + "&type=" + encodeURIComponent(n) : "magnet:" === l[o].protocol && (l[o].href = shortez_url + "full/?api=" + encodeURIComponent(shortez_api_token) + "&url=" + shortez_base64_encode(l[o].href) + "&type=" + encodeURIComponent(n))
                    } else
                for (var o = 0; o < l.length; o++)(t = shortez_get_host_name(l[o].getAttribute("href"))).length > 0 && shortez_domains.indexOf(t) > -1 ? l[o].href = shortez_url + "full/?api=" + encodeURIComponent(shortez_api_token) + "&url=" + shortez_base64_encode(l[o].href) + "&type=" + encodeURIComponent(n) : "magnet:" === l[o].protocol && (l[o].href = shortez_url + "full/?api=" + encodeURIComponent(shortez_api_token) + "&url=" + shortez_base64_encode(l[o].href) + "&type=" + encodeURIComponent(n))
        }
    });
</script>
