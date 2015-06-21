<h1>Login</h1>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" ></script>

<a href="#" id="signInButton">Sign in with Google</a>

<script type="text/javascript">
$(document).ready(function() {
  $('#signInButton').click(function() {
    $(this).attr('href','https://accounts.google.com/o/oauth2/auth?scope=' +
      'https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fplus.login&' +
      'state=generate_a_unique_state_value&' +
      'redirect_uri='+<?= json_encode($url); ?>+
      '&response_type=code&' +
      'client_id=214252622432-65it53kpd2d7ml444ibuf3d7v89q0j0q.apps.googleusercontent.com&' +
      'access_type=offline');
      return true; // Continue with the new href.
  });
});
</script>