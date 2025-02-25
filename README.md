      ENDPOINTS

1. REGISTER USER
Endpoint:http://localhost:8000/api/register
Method: POST
example
{
    "name": "Henry",
    "email": "enry@gmail.com",
    "password": "12345678",
    "password_confirmation": "12345678"
}

2. LOG IN
Endpoint: http://localhost:8000/api/login
Method POST
Example
{
    
    "email": "henry@gmail.com",
    "password": "12345678"
}

3. CREATE JOB
Endpoint: http://127.0.0.1:8000/api/jobs-create
Method: POST
Example:
{
    "title": "Driving",
    "description": "Work is looking for me and you",
    "company": "Kabale",
    "location": "Remote",
    "salary": 8000,
    "type": "part-time",
    "status": "active",
    "category":"Transport",
    "user_id":"1"
}

4. FILTER JOBS
Endpoint: http://localhost:8000/api/jobs?1?status=active&category=Development
Method: GET

5. FETCH JOBS
Endpoint: http://127.0.0.1:8000/api/fetch-jobs
Method : GET

6. APPLICATION 
Endpoint: http://localhost:8000/api/applications
Example
{
  "job_id": 1,
  "user_id": 2,
  "resume_url": "https://i/resumes/johndoe.pdf",
  "cover_letter": "Here comes my cover letter"
}
