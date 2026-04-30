<!-- FOOTER -->
<footer>
  <div class="container">
    <div class="footer-top grid-4">
      <div class="foot-col span-2">
        <a href="index.php" class="logo-img"><img src="logo.png" alt="SumoMedia" width="130" style="height:auto;"></a>
        <p class="mt-3 opacity-70 max-w-400">A performance-first growth partner for ambitious brands ready to scale through revenue engineering and data attribution.</p>
      </div>
      <div class="foot-col">
        <h4>Capabilities</h4>
        <a href="index.php#services">SEO Engine</a>
        <a href="index.php#services">PPC Scale</a>
        <a href="index.php#services">Revenue CRO</a>
        <a href="index.php#services">Attribution</a>
      </div>
      <div class="foot-col">
        <h4>Legal</h4>
        <a href="terms.php">Terms & Conditions</a>
        <a href="privacy.php">Privacy Policy</a>
        <a href="disclaimer.php">Disclaimer</a>
      </div>
    </div>
    <div class="footer-mid mt-5 pt-4 border-top">
      <div class="connect">
        <span>Connect</span>
        <a href="mailto:connect@sumomedia.in">connect@sumomedia.in</a>
      </div>
      <div class="locations text-right">
        Ahmedabad · Kochi · Dubai · Chicago
      </div>
    </div>
    <div class="footer-bottom mt-5 text-center opacity-50">
      &copy; <?= date('Y') ?> SumoMedia.in &mdash; A division of Trisora Group.<br>
      Rankings + Results = Revenue.
    </div>
  </div>
</footer>

<?php require_once __DIR__ . '/chat-widget.php'; ?>
<?= $custom_code_footer ?? '' ?>
