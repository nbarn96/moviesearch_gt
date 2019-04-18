from flask import render_template
import pymysql
from app import app

# Set up DB connection.
db_user = "yudong"
db_pass = "pEf=f1ti+uxo"
db_name = "movies"
db_host = "nbarn.io"
conn = pymysql.connect(db_host, db_user, db_pass, db_name)
cur = conn.cursor()

# Render the home page seen by all logged-in users.
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
@app.route('/', methods = ['POST'])
def search():
    query = request.form['query']

    if "episode:" in query: # Search for episodes.
        cur.execute("")

    elif "director:" in query: # Search for directors.
        cur.execute("")

    elif "actor:" in query: # Search for actors.
        cur.execute("")

    else: # Do a general search.
        cur.execute("")

    res = cur.fetchall()

    return res
