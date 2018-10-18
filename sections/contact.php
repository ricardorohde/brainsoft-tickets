<section id ="contact" class="section-padding">
  <div class="container">
    <div class="row">
      <div class="header-section text-center">
        <h2>Fale Conosco</h2>
        <p>Agende uma visita sem custos ou compromisso.
           Solicite uma demonstração online. É fácil.<br><br>

           Entre em contato conosco<br>
           Pelo fone (42) 3622-6733 ou email contato@brainsoftsistemas.com.br</p>
        <hr class="bottom-line">
      </div>
      <div id="sendmessage">Your message has been sent. Thank you!</div>
      <div id="errormessage"></div>

      <form id="form_contato" action="contactForm/send.php" name="form_contato" method="post">
          <div class="col-md-6 col-sm-6 col-xs-12 left">
            <div id="selectAssunto" class="form-group">
              <select class="selectpicker form-control assunto" name="subject">
                <option value="Sistema Imob">Sistema Imob</option>
                <option value="Digitalização de Documentos">Digitalização de Documentos</option>
                <option value="Consulta de Processos">Consulta de Processos</option>
                <option value="Imob WebBackup">Imob WebBackup</option>
              </select>
            </div>
            <div class="form-group">
                <input type="text" name="name" class="form-control form" id="name" placeholder="Seu nome" />
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" id="email" placeholder="Seu e-mail" />
            </div>
            <div class="form-group">
                <input type="text" class="form-control phone-mask" name="phone" id="phone" placeholder="Seu Telefone" />
            </div>
          </div>
          
          <div class="col-md-6 col-sm-6 col-xs-12 right">
            <div class="form-group">
                <textarea class="form-control" name="message" rows="8" data-rule="required" placeholder="Escreva sua mensagem"></textarea>
            </div>
          </div>
          
          <div class="col-xs-12">
            <!-- Button -->
            <button type="submit" id="submit" name="submit" class="form contact-form-button light-form-button oswald light">Enviar Mensagem</button>
          </div>
      </form>
      
    </div>
  </div>
</section>