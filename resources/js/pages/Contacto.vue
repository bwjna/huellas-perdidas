<template>
    <div class="contacto-wrapper">

        <div class="sticker sticker1">🐾 HUELLAS PERDIDAS</div>
        <div class="sticker sticker2">CONTACTO</div>

        <div class="contact-layout">
            <div class="contact-panel">

                <!-- IZQUIERDA -->
                <div class="side-info">
                    <h1>Comunicanos</h1>
                    <p>
                        Si encontraste algún error en la página, falta información importante o tenés alguna sugerencia
                        para mejorar la experiencia, no dudes en escribirnos. También podés contactarnos si te interesa
                        colaborar, apoyar o patrocinar el proyecto para ayudarnos a seguir reuniendo mascotas con sus
                        familias.
                    </p>

                    <div class="info-card">
                        <i class="bi bi-envelope-fill"></i>
                        huellasperdidas@gmail.com
                    </div>
                    <div class="info-card">
                        <i class="bi bi-geo-alt-fill"></i>
                        Buenos Aires, Argentina
                    </div>

                    <div class="socials">
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-tiktok"></i></a>
                        <a href="#"><i class="bi bi-facebook"></i></a>
                    </div>
                </div>

                <!-- DERECHA -->
                <div class="form-side">
                    <h2>Mandanos un mensaje</h2>
                    <span>Te respondemos apenas podamos</span>

                    <div class="input-box">
                        <label>Nombre</label>
                        <input v-model="form.nombre" type="text" placeholder="Tu nombre" required>
                    </div>
                    <div class="input-box">
                        <label>Email</label>
                        <input v-model="form.email" type="email" placeholder="correo@email.com" required>
                    </div>
                    <div class="input-box">
                        <label>Mensaje</label>
                        <textarea v-model="form.mensaje" rows="6" placeholder="Escribí algo..." required></textarea>
                    </div>

                    <button class="btn-send" @click="enviar">Enviar mensaje</button>
                </div>

            </div>
        </div>

    </div>
</template>

<script setup>
import { reactive } from 'vue'

const form = reactive({
    nombre: '',
    email: '',
    mensaje: '',
})

function enviar() {
    // TODO: conectar con el backend (axios o Inertia useForm)
    console.log('Formulario enviado:', form)
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&display=swap');

* {
    font-family: 'Space Grotesk', sans-serif;
}

.contacto-wrapper {
    min-height: 100vh;
    background: #f4f1ea;
    overflow-x: hidden;
    position: relative;
}

/* GRID FONDO */
.contacto-wrapper::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(0,0,0,.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,0,0,.05) 1px, transparent 1px);
    background-size: 40px 40px;
    z-index: 0;
}

/* STICKERS */
.sticker {
    position: absolute;
    padding: 14px 22px;
    border-radius: 999px;
    font-weight: 700;
    z-index: 2;
    box-shadow: 0 10px 25px rgba(0,0,0,.12);
}
.sticker1 {
    top: 40px;   
    left: 60px;
    background: #111;
    color: #fff;
    transform: rotate(-8deg);
}
.sticker2 {
    bottom: 80px;
    right: 60px;
    background: #ff7b00;
    color: #fff;
    transform: rotate(8deg);
}

/* LAYOUT */
.contact-layout {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 100px 60px;
    position: relative;
    z-index: 3;
}

/* PANEL */
.contact-panel {
    width: 100%;
     max-width: 900px; 
    background: white;
    border: 4px solid #111;
    border-radius: 35px;
    overflow: hidden;
    display: grid;
     grid-template-columns: 360px 1fr;
    box-shadow: 18px 18px 0 #111;
}

/* IZQUIERDA */
.side-info {
    background: #ff7b00;
    padding: 35px 30px; 
    color: white;
}
.side-info h1 {
    font-size: 38px;
    line-height: .9;
    font-weight: 700;
    margin-bottom: 25px;
}
.side-info p {
    line-height: 1.7;
    margin-bottom: 40px;
    font-size: 13px;  
}

/* TARJETAS INFO */
.info-card {
    background: #fff;
    color: #555;
    border-radius: 22px;
    padding: 10px 14px;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    border: 4px solid #111;
    box-shadow: 0 6px 0 #111;
    font-weight: 600;
    transition: .25s;
    overflow-wrap: anywhere;
}
.info-card:hover {
    transform: translateY(-3px);
}
.info-card i {
    font-size: 20px;
    color: #333;
}

/* REDES */
.socials {
    display: flex;
    gap: 14px;
    margin-top: 20px;   
}
.socials a {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: rgba(255,255,255,.18);
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    text-decoration: none;
    font-size: 18px;
    transition: .3s;
    border: 1px solid rgba(255,255,255,.25);
    backdrop-filter: blur(10px);
}
.socials a:hover {
    background: rgba(255,255,255,.3);
    transform: translateY(-4px);
}

/* DERECHA */
.form-side {
    padding: 35px 40px;
    background: #fffdf9;
}
.form-side h2 {
    font-size: 34px;
    color: #111;
    margin-bottom: 10px;
    font-weight: 700;
}
.form-side span {
    color: #666;
    display: block;
    margin-bottom: 40px;
}

/* INPUTS */
.input-box {
    margin-bottom: 20px;
}
.input-box label {
    display: block;
    margin-bottom: 10px;
    font-weight: 700;
    color: #111;
}
.input-box input,
.input-box textarea {
    width: 100%;
    padding: 12px; 
    border-radius: 18px;
    border: 3px solid #111;
    background: white;
    outline: none;
    transition: .2s;
    font-size: 15px;
}
.input-box input:focus,
.input-box textarea:focus {
    transform: translate(-4px, -4px);
    box-shadow: 8px 8px 0 #ff7b00;
}
textarea {
    resize: none;
}

/* BOTÓN */
.btn-send {
    width: 100%;
    padding: 13px;
    border-radius: 20px;
    background: #111;
    color: white;
    font-weight: 700;
    font-size: 16px;
    transition: .2s;
    border: 3px solid #111;
    cursor: pointer;
}
.btn-send:hover {
    background: #ff7b00;
    transform: translate(-4px, -4px);
    box-shadow: 8px 8px 0 #111;
}

/* RESPONSIVE */
@media (max-width: 950px) {
    .contact-panel {
        grid-template-columns: 1fr;
    }
    .side-info,
    .form-side {
        padding: 40px 28px;
    }
    .side-info h1 {
        font-size: 52px;
    }
    .form-side h2 {
        font-size: 38px;
    }
    .sticker1,
    .sticker2 {
        display: none;
    }
}

/* Celulares angostos: los tamaños de la tablet siguen siendo muy grandes acá */
@media (max-width: 480px) {
    .contact-layout {
        padding: 40px 16px;
    }
    .contact-panel {
        border-width: 3px;
        border-radius: 24px;
        box-shadow: 10px 10px 0 #111;
    }
    .side-info,
    .form-side {
        padding: 24px 20px;
    }
    .side-info h1 {
        font-size: 30px;
        line-height: 1.1;
    }
    .side-info p {
        font-size: 13px;
        margin-bottom: 24px;
    }
    .form-side h2 {
        font-size: 24px;
    }
    .form-side span {
        margin-bottom: 24px;
        font-size: 13px;
    }
    .info-card {
        padding: 10px 12px;
        font-size: 13px;
        gap: 10px;
    }
    .info-card i {
        font-size: 16px;
        flex-shrink: 0;
    }
    .socials a {
        width: 42px;
        height: 42px;
    }
}
</style>