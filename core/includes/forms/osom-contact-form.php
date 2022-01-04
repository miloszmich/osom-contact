<?php
/**
 * Osom contact form
 *
 * @package       OSOMCONTACT
 * @author        Miłosz Michałkiewicz
 * @version       1.0.0
 */

?>

<div id="container">

  <object class="osomContactFormLogo" data="<?php echo OSOMCONTACT_PLUGIN_URL . 'core/includes/assets/images/osom_logo.png';?>" width="250" height="142"></object>

  <form id="osomContactForm" method="post">

    <div class="osom-column">
      <div>
        <label for="osomContactFirstName">First name:*</label>
        <input type="text" name="osomContactFirstName" id="osomContactFirstName" value="" required />
      </div>

      <div>
        <label for="osomContactLastName">Last name:*</label>
        <input type="text" name="osomContactLastName" id="osomContactLastName" value="" required />
      </div>

      <div>
        <label for="osomContactLogin">Login:*</label>
        <input type="text" name="osomContactLogin" id="osomContactLogin" value="" required />
      </div>
    </div>

    <div class="osom-column">
      <div>
        <label for="osomContactEmail">User e-mail:*</label>
        <input type="text" name="osomContactEmail" id="osomContactEmail" value="" required />
      </div>

      <div>
        <label for="osomContactCity">City:*</label>
        <select name="osomContactCity" id="osomContactCity">
          <option value="Łódź">Łódź</option>
          <option value="Poznań">Poznań</option>
          <option value="Warszawa">Warszawa</option>
        </select>
      </div>

      <div>
        <input id="osomContactSubmitBtn" type="submit" disabled></input>
      </div>
    </div>

    <div class="osom-column">
      <div>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
          magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
          consequat. </p>
      </div>

      <div>
        <input type="checkbox" id="osomContactAgree" name="osomContactAgree" required>
        <label for="osomContactAgree">Agree*</label>
      </div>
    </div>
  </form>
</div>