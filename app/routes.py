from flask import render_template
from app import app

# Render the home page seen by all users.
@app.route('/')
def index():
    return render_template('index.html')

# Search for movies.
@app.route('/search')
def search():
    return render_template('search.html')
