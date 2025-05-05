<section style="background-color: #f3f0ff; padding: 3rem 1rem; font-family: 'Segoe UI', sans-serif; color: #333;">
  <div style="max-width: 1000px; margin: auto; text-align: center;">

    <!-- Featured Voucher Image -->
    <img src="http://workfyre.local/assets/images/YAM-is-On-06-01.png" alt="YAM-is-ON Trading Voucher"
      style="width: 100%; max-width: 1000px; border-radius: 16px; margin-bottom: 2rem; box-shadow: 0 4px 14px rgba(0,0,0,0.2);" />

    <h1 style="font-size: 2.5rem; color: #4b0082; margin-bottom: 1rem;">🌊 How It Works</h1>
    <p style="font-size: 1.25rem; line-height: 1.6; margin-bottom: 2rem;">
      The smallest acts — a box, a scan, a story — can unlock real community value.
      This is the <strong>Krill Movement</strong>, powered by AI, delivered through Proof of Delivery.
      <br><strong>Welcome to “YAM-is-ON” Delivery.</strong>
    </p>

    <div
      style="text-align: left; background-color: #fff; padding: 2rem; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
      <h2 style="color: #663399; font-size: 1.75rem; margin-bottom: 1rem;">📦 1. Find a Box</h2>
      <p>Gather items you no longer need — books, tools, heirlooms — and give them a second life.</p>

      <h2 style="color: #663399; font-size: 1.75rem; margin-top: 2rem;">🎫 2. Add a YAM Voucher Sticker</h2>
      <p>Buy a <strong>10-pack for $3</strong>. Each sticker (just 30¢) transforms your delivery into a trackable story
        and earns community value.</p>

      <h2 style="color: #663399; font-size: 1.75rem; margin-top: 2rem;">📲 3. Scan Twice — Delivery Done</h2>
      <ul style="padding-left: 1.5rem; list-style: disc;">
        <li><strong>Seller scans</strong> to name the buyer.</li>
        <li><strong>Buyer scans</strong> on arrival.</li>
        <li>Two scans = <strong>Proof of Delivery (PoD)</strong> — no middleman required.</li>
      </ul>

      <h2 style="color: #663399; font-size: 1.75rem; margin-top: 2rem;">🤖 4. Let AI Assist You</h2>
      <p>
        From pricing to storytelling, AI is here to help you every step — especially helpful for seniors.
        Our system acts like a friendly memory specialist in your pocket.
      </p>

      <h2 style="color: #663399; font-size: 1.75rem; margin-top: 2rem;">🦐 5. Join the Krill — Not the Whale</h2>
      <p>
        Alone, you’re small. But together, like krill, we move the ocean.
        This system organizes small acts to starve wasteful systems. We don’t feed the whales — we organize to outswim
        them.
      </p>

      <h2 style="color: #663399; font-size: 1.75rem; margin-top: 2rem;">💜 6. Earn + Share in the Cookie Jar Economy
      </h2>
      <p>
        Every scan delivers value. Buyers earn 7%. Sellers earn 3%.
        Each month’s profits are shared on the 1st. <strong>Redemption Day</strong> is every September 1st.
        <br>This is <strong>member capitalism</strong> — where dignity, not dollars, leads the way.
      </p>
    </div>

    <!-- Call to Action Section -->
    <div style="margin-top: 3rem;">
      <h2 style="color: #4b0082;">🚀 Ready to Start?</h2>
      <p style="font-size: 1.1rem; max-width: 700px; margin: 1rem auto;">
        All it takes is one sticker, one scan, and one story.
        <br><strong>Let there be peace on Earth… and let it begin with your box.</strong>
      </p>
      <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; margin-top: 1rem;">
        <a id="buyYamVouchers" href="#"
          style="background-color: #6a0dad; color: #fff; padding: 1rem 2rem; border-radius: 10px; text-decoration: none; font-weight: bold;">🛒
          Buy 10-Pack ($3)</a>
        <a id="joinaPoc" href="#"
          style="background-color: #9370db; color: #fff; padding: 1rem 2rem; border-radius: 10px; text-decoration: none; font-weight: bold;">👥
          Join a POC</a>
        <!-- /ai-onboarding -->
        <a id="getAiHelp" href="#"
          style="background-color: #b19cd9; color: #fff; padding: 1rem 2rem; border-radius: 10px; text-decoration: none; font-weight: bold;">🤖
          Get AI Help</a>
      </div>
    </div>

    <!-- Footer Note -->
    <div style="margin-top: 4rem; font-style: italic; color: #444;">
      “YAM-is-ON” Delivery | Atlanta, USA | Member Treasury | Détente 2.0 World Peace
    </div>
  </div>
  <!-- Popup Modal -->
  <div id="yamModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">

    <div style="background:white; padding:20px; border-radius:8px; max-width:700px; position:relative; width:100%">
      <button id="closeModal"
        style="position:absolute; top:10px; right:10px; background:transparent; color:black; border:none; font-weight: 500; font-size:18px; cursor:pointer;">
        ✕
      </button>

      <h2>How It Works</h2>
      <div id="popupContent" style="margin-bottom:20px;">
        <!-- Local or hosted video -->
        <video style="margin-bottom:20px;" width="100%" controls>
          <source src="http://workfyre.local/assets/images/Delivering Legacy The Purpose Party_1080p.mp4"
            type="video/mp4">
        </video>
      </div>
      <div id="triggeredButtonContainer"></div>
    </div>
  </div>



  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const buyButton = document.getElementById('buyYamVouchers');
      const joinaPoc = document.getElementById('joinaPoc');
      const getAiHelp = document.getElementById('getAiHelp');
      const modal = document.getElementById('yamModal');
      const closeBtn = document.getElementById('closeModal');
      const triggeredButtonContainer = document.getElementById('triggeredButtonContainer');
      const popupContent = document.getElementById('popupContent'); // Make sure you have this in HTML

      // Buy Button
      buyButton.addEventListener('click', function (e) {
        e.preventDefault();
        popupContent.innerHTML = `<!-- Local or hosted video -->
        <video style="margin-bottom:20px;" width="100%" controls>
          <source src="http://workfyre.local/assets/images/Delivering Legacy The Purpose Party_1080p.mp4"
            type="video/mp4">
        </video>`;
        triggeredButtonContainer.innerHTML = `
                <a href="https://www.smallstreet.app/product/yam-is-on-sticker/" 
            target="_blank"
            style="background-color: #6a0dad; color: #fff; padding: 1rem 2rem; border-radius: 10px; text-decoration: none; font-weight: bold;">
            🛒 Buy 10-Pack ($3)
          </a>`;
        modal.style.display = 'flex';
      });

      // Join a POC Button
      joinaPoc.addEventListener('click', function (e) {
        e.preventDefault();
        popupContent.innerHTML = `<!-- Local or hosted video -->
        <video style="margin-bottom:20px;" width="100%" controls>
          <source src="http://workfyre.local/assets/images/Delivering Legacy The Purpose Party_1080p.mp4"
            type="video/mp4">
        </video>`;
        triggeredButtonContainer.innerHTML = `
      <a id="joinaPocInsidePopup" href="#"  target="_blank"
        style="background-color: #9370db; color: #fff; padding: 1rem 2rem; border-radius: 10px; text-decoration: none; font-weight: bold;">
        👥 Join a POC
      </a>`;
        modal.style.display = 'flex';
      });

      // Get AI Help Button
      getAiHelp.addEventListener('click', function (e) {
        e.preventDefault();
        popupContent.innerHTML = `<!-- Local or hosted video -->
        <video style="margin-bottom:20px;" width="100%" controls>
          <source src="http://workfyre.local/assets/images/Delivering Legacy The Purpose Party_1080p.mp4"
            type="video/mp4">
        </video>`;
        triggeredButtonContainer.innerHTML = `
      <a href="#"
        style="background-color: #b19cd9; color: #fff; padding: 1rem 2rem; border-radius: 10px; text-decoration: none; font-weight: bold;">
        🤖 Get AI Help
      </a>`;
        modal.style.display = 'flex';
      });

      triggeredButtonContainer.addEventListener('click', function (e) {
        if (e.target && e.target.id === 'joinaPocInsidePopup') {
          e.preventDefault();

          // Inject GraceBook content
          popupContent.innerHTML = `
        <div style="text-align: center; padding: 1rem;">
          <h3>🟣 Join the GraceBook Community</h3>
          <p>To participate fully, prepare the following:</p>
          <ul style="list-style: none; padding: 0;">
            <li>✅ QRTiger vCard Profile</li>
            <li>✅ PayPal or Venmo Account</li>
            <li>✅ Mobile Device (Smartphone preferred)</li>
            <li>✅ Valid Email Address</li>
          </ul>
          <a href="https://discord.gg/Z77f4CEd" target="_blank"
            style="font-size: 1.2rem; background: purple; color: white; padding: 10px 20px; border-radius: 10px; text-decoration: none;">
            Enter GraceBook Server
          </a>
        </div>
      `;
        }
      });

      // Close modal button
      closeBtn.addEventListener('click', function () {
        modal.style.display = 'none';
        const video = modal.querySelector('video');
        if (video) video.pause();
      });

      // Close modal on outside click
      modal.addEventListener('click', function (e) {
        if (e.target === modal) {
          modal.style.display = 'none';
          const video = modal.querySelector('video');
          if (video) video.pause();
        }
      });
    });

  </script>

</section>