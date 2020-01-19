from flask import Flask, render_template_string, request

app = Flask(__name__)
app.config['FLAG'] = 'flag{blah_blah_blah}'

@app.route('/')
def index():
    display = '''
    <p>Oh, I dropped the flag in the flask :(</p>
    <form action="/flask" method="POST">
        <input type="text" name="search">
        <input type="submit" value="Find">
    </form>
    '''
    return render_template_string(display)

@app.route('/flask', methods=['POST'])
def search():
    search = request.form['search']
    display = '<p>Is there the flag?</p>{}'.format(search)
    return render_template_string(display)

if __name__ == '__main__':
    app.run()