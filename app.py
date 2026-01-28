from flask import Flask, render_template, request, redirect, url_for, session, flash, jsonify
import mysql.connector
import bcrypt
from flask import Flask, render_template


@app.route("/")
def home():
    return render_template("index.html")


app = Flask(__name__)
app.secret_key = 'clave_secreta_noba_2026'

# --- CONEXIÓN A BASE DE DATOS ---
def get_db_connection():
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="tu_base_de_datos" 
    )

# --- RUTAS DE NAVEGACIÓN ---

@app.route('/')
def home():
    return render_template('index.html')

@app.route('/login')
def login_page():
    return render_template('login.html')

@app.route('/registro')
def registro_page():
    return render_template('registro.html')

@app.route('/dashboard')
def dashboard():
    # Solo permite entrar si hay una sesión activa
    if 'user_id' in session:
        return f"Bienvenido al Panel de Control de NOBA, {session.get('nombre')}."
    return redirect(url_for('login_page'))

# --- LÓGICA DE PROCESAMIENTO ---

# NUEVA RUTA: Recibe el éxito del reconocimiento facial
@app.route('/login_facial', methods=['POST'])
def login_facial():
    # Aquí puedes añadir lógica para identificar QUIÉN es el usuario. 
    # Por ahora, simulamos un inicio de sesión exitoso de un usuario invitado o bio-verificado.
    session['user_id'] = 999  # ID temporal o real
    session['nombre'] = "Usuario Biométrica"
    return jsonify({"status": "success", "message": "Identidad validada"}), 200

@app.route('/ejecutar_login', methods=['POST'])
def ejecutar_login():
    email = request.form.get('email')
    password = request.form.get('password')

    try:
        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT * FROM usuarios WHERE email = %s", (email,))
        user = cursor.fetchone()
        cursor.close()
        conn.close()

        if user and bcrypt.checkpw(password.encode('utf-8'), user['password'].encode('utf-8')):
            session['user_id'] = user['id']
            session['nombre'] = user['nombre']
            return redirect(url_for('dashboard')) # Cambiado para que redirija
        else:
            return "Error: Credenciales incorrectas", 401
    except Exception as e:
        return f"Error de conexión: {str(e)}", 500

@app.route('/procesar_registro', methods=['POST'])
def procesar_registro():
    nombre = request.form.get('nombre', 'Usuario Noba')
    email = request.form.get('email')
    password = request.form.get('password')
    confirm_password = request.form.get('confirm') 

    if password != confirm_password:
        return "Error: Las contraseñas no coinciden", 400

    hashed_pw = bcrypt.hashpw(password.encode('utf-8'), bcrypt.gensalt()).decode('utf-8')

    try:
        conn = get_db_connection()
        cursor = conn.cursor()
        sql = "INSERT INTO usuarios (nombre, email, password) VALUES (%s, %s, %s)"
        cursor.execute(sql, (nombre, email, hashed_pw))
        conn.commit()
        cursor.close()
        conn.close()
        
        return redirect(url_for('login_page'))
    except mysql.connector.Error as err:
        return f"Error en la base de datos: {err}", 500

# RUTA PARA CERRAR SESIÓN
@app.route('/logout')
def logout():
    session.clear()
    return redirect(url_for('home'))

if __name__ == '__main__':
    app.run(debug=True)