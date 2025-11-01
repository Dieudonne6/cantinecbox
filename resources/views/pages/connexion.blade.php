<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/typicons/typicons.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
      body {
       
        color: white;
        margin: 0;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(-45deg, #2b1055, #2c364bff, #1b2845, #1c1b2dff);
        background-size: 400% 400%;
        animation: gradientMove 10s ease infinite;
        font-family: 'Poppins', sans-serif;
        overflow: hidden;
      }


       @keyframes gradientMove {
        0% {
          background-position: 0% 50%;
        }
        50% {
          background-position: 100% 50%;
        }
        100% {
          background-position: 0% 50%;
        }
      }

   

      .login-container {
        display: flex;
        width: 700px;
        height: 380px;
        border-radius: 10px;
        overflow: hidden;
        background: #021526;
        box-shadow: 0 0 20px #021526;
        margin-bottom: 80px; /* espace avant footer */
         margin-top: 5rem;
      }

        #bgParticles {
      position: fixed;
      top: 0; left: 0;
      width: 100vw;
      height: 100vh;
      z-index: 0;
      pointer-events: none;
      }


      .login-form {
        width: 50%;
        background: #031b33;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
      }

      .login-form h2 {
        text-align: center;
        margin-bottom: 25px;
        font-size: 22px;
        font-weight: 600;
      }

      .input-group {
        position: relative;
        margin-bottom: 25px;
      }

      .input-group input {
        width: 80%;
        padding: 10px 35px;
        border: none;
        border-bottom: 2px solid #9b6bff;
        background: transparent;
        color: #fff;
        outline: none;
        font-size: 14px;
      }

      .input-group i {
        position: absolute;
        left: 5px;
        top: 50%;
        transform: translateY(-50%);
        color: #9b6bff;
        font-size: 18px;
      }

      .login-btn {
        width: 100%;
        background: linear-gradient(90deg, #6b7cff, #9b6bff);
        border: none;
        color: white;
        padding: 10px;
        border-radius: 25px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
      }

      .login-btn:hover {
        transform: scale(1.03);
        box-shadow: 0 0 10px #9b6bff;
      }

      .welcome-section {
        width: 50%;
        background: linear-gradient(90deg, #6b7cff, #9b6bff);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        text-align: center;
        padding: 20px;
      }

      .welcome-section h1 {
        font-size: 30px;
        margin-bottom: 10px;
      }

      .welcome-section p {
        font-size: 20px;
        line-height: 1.2;
      }

      footer {
        position: fixed;
        bottom: 10px;
        width: 100%;
        text-align: center;
        font-size: 13px;
        color: #ccc;
      }

      footer a {
        color:   linear-gradient(90deg, #6b7cff, #9b6bff);;
        text-decoration: none;
        font-weight: bold;
      }

      @media (max-width: 750px) {
        .login-container {
          flex-direction: column;
          width: 90%;
          height: auto;
        }
        .login-form, .welcome-section {
          width: 100%;
        }
        .welcome-section {
          padding: 30px 20px;
        }
      }

        /* ------------------ FOOTER (RAJEUNI) ------------------ */
        footer.footer {
          position: fixed;
          left: 0;
          bottom: 0;
          width: 100%;
          z-index: 2;
          display: flex;
          align-items: center;
          justify-content: space-between;
          gap: 12px;
          padding: 18px 40px;
          box-sizing: border-box;
          /* subtle translucent dark strip to fit the page */
          background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(10,14,25,0.25));
          backdrop-filter: blur(8px) saturate(120%);
          -webkit-backdrop-filter: blur(8px) saturate(120%);
          border-top: 1px solid rgba(255,255,255,0.04);
          color: rgba(225,225,245,0.78);
          font-size: 13px;
        }

        footer.footer .left,
        footer.footer .right {
          display:flex;
          align-items:center;
          gap: 12px;
          flex-wrap: wrap;
        }

        footer.footer a {
          color: rgba(167,154,255,0.95);
          text-decoration: none;
          font-weight: 600;
        }
        footer.footer a:hover {
          text-decoration: underline;
          opacity: 0.95;
        }

        .filigramme,
         .alert {
          position: absolute;

          margin-top: -32rem;
          width: 100%;
          text-align: center;
          font-size: 25px;
          color: white;
          white-space: nowrap;
          overflow: hidden;

        }

        .filigramme::after {
          content: attr(text);
        }

        @keyframes defilement {
          0% { transform: translateX(100%); }
          100% { transform: translateX(-100%); }
      }
     
     

    </style>
  </head>

  <body>
      
    <p class="filigramme">SchoolBox, votre logiciel de gestion Scolaire !</p>

    <div class="login-container">
      <br><br>
      <div class="welcome-section">
      
        <h1>Bienvenue</h1>
        <p>Entrez vos identifiants pour vous connectez !</p>
      </div>

      <div class="login-form">
        <h2>Connexion</h2>
        <form action="{{ url('logins') }}" method="POST">
          {{ csrf_field() }}
          <input type="text" style="display:none" autocomplete="username">
          <input type="password" style="display:none" autocomplete="new-password">
          <div class="input-group">
            <i class="typcn typcn-user"></i>
            <input
              type="text"
              name="login_usercontrat"
              class="form-control"
              placeholder="Entrer votre nom"
              aria-label="Adresse e-mail"
              required        
            />
          </div>
          <div class="input-group">
            <i class="typcn typcn-lock-closed"></i>
            <input
            type="password"
            name="password_usercontrat"
            id="passwordInput"
            class="form-control"
            placeholder="Entrer votre mot de passe"
            aria-label="Mot de passe"
            required
          />
          </div>
          <button type="submit" class="login-btn">Se connecter</button>
        </form>
      </div>
    
    </div>

    <footer class="footer" role="contentinfo">
      <div class="left">
        <span>Copyright &copy; {{ date('Y') }}</span>
        <span>•</span>
        <a href="https://www.cbox.bj" rel="noopener">C BOX Système intégré de gestion académique</a>
        <span>•</span>
        <span>Tous droits réservés.</span>
        <footer>
    
        </footer>   
      </div>
      <div class="right">
        <span>SchoolBox</span>
       <a href="https://wa.me/22997791717" rel="noopener">+229 01 97 79 17 17</a>
      </div>
    </footer>


    <!-- //Pour l'arière plan
    <canvas id="bgParticles"></canvas>
    <script>
      const canvas = document.getElementById('bgParticles');
      const ctx = canvas.getContext('2d');
      canvas.width = innerWidth;
      canvas.height = innerHeight;

      let particles = Array.from({length: 40}, () => ({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        r: Math.random() * 2 + 1,
        dx: (Math.random() - 0.5) * 0.6,
        dy: (Math.random() - 0.5) * 0.6
      }));

      function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = "rgba(255,255,255,0.4)";
        particles.forEach(p => { 
          ctx.beginPath();
          ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
          ctx.fill();
          p.x += p.dx;
          p.y += p.dy;
          if (p.x < 0 || p.x > canvas.width) p.dx *= -1;
          if (p.y < 0 || p.y > canvas.height) p.dy *= -1;
        });
        requestAnimationFrame(animate);
      }

      animate();
      window.addEventListener('resize', () => {
        canvas.width = innerWidth;
        canvas.height = innerHeight;
      });
    </script> -->
    <!-- //Pour l'arière plan fin -->

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>
