import sys
import json
from numpy import*
#from pdb import *;



#####################################################
### EDIT THIS VARIABLE FOR OUTPUT FILE		#####
 						#####
outputfile = "TermResultsCostOfRevenue.csv"			#####
						#####
### EDIT outputfile to change the name of the output file. needs to be within quotes
#####################################################

if len(sys.argv) > 1:
    line_generator = open(sys.argv[1])
else:
    line_generator = sys.stdin

def find_value(ordered_pairs):
    d = {}
    for k, v in ordered_pairs:
        if k == "value":
           d.setdefault(k, []).append(v)
        else:
           d[k] = v
    return d

def ifequal(iterator):
	return len(set(iterator)) <= 1
	
###################################################################################
# sets varaiables and starts first 5 entries of csv printout array

for line in line_generator:
    
    bool1 = True
    bool2 = True
    bool3 = True 	
    n=0
    value1 = []
    value2 = []
    expression1 = []
    expression2 = []
    csv = []

    line_object = json.loads(line,object_pairs_hook=find_value) 
    value = str(line_object["value"])  
    entityId = str(line_object["entityId"])
    FY = str(line_object["FY"])

    FQ = str(line_object["FQ"])
    
   


    try:
    	elementName = str(line_object["elementName"])
    except KeyError, e:
	elementName = "invalid elementName"

    csv = [entityId, FY,FQ, value, elementName]

#####################################################################################
    while bool1 == True or bool2 == True:  
	try: 
		value1.append(line_object["otherTermResults"][n]["value"])
		expression1.append(line_object["otherTermResults"][n]["expression"])
    	except IndexError:
   		bool1= False
 		#value2.append("invalid") # YOU CAN REMVOE THIS IF YOU WANT. ITS FOR TESTING PURPSOES TO SEE IF ITS LOOPING
	except KeyError, e:
		bool1 = False

	##try: 
	    	##value2.append(line_object["dimensionalFacts"][n]["value"])
		##expression2.append(line_object["dimensionalFacts"][n]["expression"])
	##except IndexError:
		##bool2 = False
		#value3.append("invalid") # YOU CAN REMVOE THIS IF YOU WANT. ITS FOR TESTING PURPSOES TO SEE IF ITS LOOPING
	##except KeyError, e:
		##bool2 = False

	n+=1
######################################################################################

		# CREATES VALUE1 and expression1 and 2 arrays

    for i in range(len(value1)):
	try:
		csv.append(str(expression1[i])) 
	except IndexError:
		csv.append("invalid expression")
	try:
		csv.append(str(value1[i]))
	except IndexError:
		csv.append("invalid value")
    for j in range(len(value2)):
	try:
		csv.append(str(expression2[j]))
	except IndexError:
		csv.append("invalid expression")
	try: 
		csv.append(str(value2[j]))
	except IndexError:
		csv.append("invalid value")

############################################################################

		# WRITES CSV TO FILE AND STRIP UNNECCESSARY CHARACTERS
   
    csv = str(csv).replace("'", "")
    csv = csv.replace(" (" , "")
    csv = csv.replace(")" , "")
    csv = csv.replace(",,", ",")
    csv = csv.replace("[", "")
    csv = csv.replace("]", "")
    csv = csv.replace("{", "")
    csv = csv.replace("}", "") 
    myfile = open(outputfile, "a") 
    myfile.write(csv)
    myfile.write("\n")
    myfile.close()



###########################################################################




# IT is of the form print(entityId, FY, FQ, value, elementName, expression1[n], value1[n], expression1[n+1], value1[n+1} ..... expression2[n], value2[n], expression2[n+1], value2[n+1]  
    
    		


