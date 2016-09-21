from websocket import create_connection
from time import sleep

# Establish websocket connection with the given IP and host
# Params are self-explanatory. Note they need to be strings
def establish_connection(ip='52.204.229.101', port='8080', type='ws'):
	conn_string = type + '://' + ip + ':' + port
	try:
		print 'Attempting {t} connection - {c}'.format(t='websocket' if type == 'ws' else type, c=conn_string)
		#ws = create_connection("ws://52.204.229.101:8080")
		ws = create_connection(conn_string)
	except ValueError, e:
		print '!!! Couldnt connect !!! Error: {}'.format(e)
		exit(0)
	else:
 		print 'Connection successful'
		return ws

# Simple - publish to the websocket.
# Params - n_iter (int): number of iterations to publish the mesage
#	 - sleep_time (int): number seconds each loop should wait
def push_messages(ws, n_iter=2, sleep_time=10):

	for i in range(n_iter):
		full_msg = '{m}'.format(i=i, m=msg)
		print 'sending {}'.format(full_msg)
		ws.send(full_msg)	
		print "Sent"
		sleep(sleep_time)

# Close the websocket connection
def close_connection():
	print 'closing websocket..'
	ws.close()

msg = '{"header": { "u_id": 0, "lift_id": 1}, "content": {"v_rms": [0, 1, 2, 3, 4], "p_rms": [5, 6, 7, 8, 9] }}'
ws = establish_connection()
push_messages(ws, 2, 10)
close_connection()
