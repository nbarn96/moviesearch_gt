from flask import render_template
from flask-mysqldb import mysql
from app import app

# Render the home page seen by all users.
@app.route('/')
def index():
    return render_template('index.html')

# Create a new user.
@app.route('/register', methods = ['POST', 'GET'])
def register_user():
    if request.method == "POST":
        user = request.form['username']
        pass = request.form['password']
        

# Search for movies.
@app.route('/search')
def search():
    return render_template('search.html')
