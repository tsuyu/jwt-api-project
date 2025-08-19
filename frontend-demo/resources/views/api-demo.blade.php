@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>JWT API Demo</h4>
                    <p class="mb-0">This demo showcases the JWT authentication API endpoints. The API is running on <code>http://localhost:8000/api</code></p>
                </div>
                <div class="card-body">
                    <!-- Token Display -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6>Current JWT Token:</h6>
                                    <div id="current-token" class="token-display text-muted">No token available</div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="clearToken()">Clear Token</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Registration -->
                        <div class="col-md-6">
                            <div class="card api-demo-card">
                                <div class="card-header">
                                    <h5>1. Register User</h5>
                                    <small class="text-muted">POST /api/auth/register</small>
                                </div>
                                <div class="card-body">
                                    <form id="register-form">
                                        <div class="mb-3">
                                            <input type="text" class="form-control" name="name" placeholder="Full Name" required>
                                        </div>
                                        <div class="mb-3">
                                            <input type="text" class="form-control" name="username" placeholder="Username" required>
                                        </div>
                                        <div class="mb-3">
                                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                                        </div>
                                        <div class="mb-3">
                                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                                        </div>
                                        <div class="mb-3">
                                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Register</button>
                                    </form>
                                    <div id="register-response" class="response-box" style="display: none;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Login -->
                        <div class="col-md-6">
                            <div class="card api-demo-card">
                                <div class="card-header">
                                    <h5>2. Login User</h5>
                                    <small class="text-muted">POST /api/auth/login</small>
                                </div>
                                <div class="card-body">
                                    <form id="login-form">
                                        <div class="mb-3">
                                            <input type="text" class="form-control" name="username" placeholder="Username" required>
                                        </div>
                                        <div class="mb-3">
                                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                                        </div>
                                        <button type="submit" class="btn btn-success">Login</button>
                                    </form>
                                    <div id="login-response" class="response-box" style="display: none;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Get User Info -->
                        <div class="col-md-6">
                            <div class="card api-demo-card">
                                <div class="card-header">
                                    <h5>3. Get User Info</h5>
                                    <small class="text-muted">GET /api/auth/me</small>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Requires authentication token</p>
                                    <button type="button" class="btn btn-info" onclick="getUserInfo()">Get User Info</button>
                                    <div id="user-info-response" class="response-box" style="display: none;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Protected Route -->
                        <div class="col-md-6">
                            <div class="card api-demo-card">
                                <div class="card-header">
                                    <h5>4. Protected Route</h5>
                                    <small class="text-muted">GET /api/protected</small>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Requires authentication token</p>
                                    <button type="button" class="btn btn-warning" onclick="accessProtected()">Access Protected Route</button>
                                    <div id="protected-response" class="response-box" style="display: none;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Refresh Token -->
                        <div class="col-md-6">
                            <div class="card api-demo-card">
                                <div class="card-header">
                                    <h5>5. Refresh Token</h5>
                                    <small class="text-muted">POST /api/auth/refresh</small>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Requires authentication token</p>
                                    <button type="button" class="btn btn-secondary" onclick="refreshToken()">Refresh Token</button>
                                    <div id="refresh-response" class="response-box" style="display: none;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Logout -->
                        <div class="col-md-6">
                            <div class="card api-demo-card">
                                <div class="card-header">
                                    <h5>6. Logout</h5>
                                    <small class="text-muted">POST /api/auth/logout</small>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Requires authentication token</p>
                                    <button type="button" class="btn btn-danger" onclick="logout()">Logout</button>
                                    <div id="logout-response" class="response-box" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentToken = null;

// CSRF token for Laravel
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Update token display
function updateTokenDisplay(token) {
    currentToken = token;
    if (token) {
        $('#current-token').text(token).removeClass('text-muted').addClass('text-success');
    } else {
        $('#current-token').text('No token available').removeClass('text-success').addClass('text-muted');
    }
}

// Clear token
function clearToken() {
    updateTokenDisplay(null);
}

// Display response
function displayResponse(elementId, response) {
    const element = $('#' + elementId);
    element.text(JSON.stringify(response, null, 2)).show();
    
    // Auto-extract token from successful login/register responses
    if (response.data && response.data.token && (elementId === 'login-response' || elementId === 'register-response')) {
        updateTokenDisplay(response.data.token);
    }
    
    // Auto-extract token from refresh response
    if (response.data && response.data.token && elementId === 'refresh-response') {
        updateTokenDisplay(response.data.token);
    }
}

// Register form
$('#register-form').on('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        name: $(this).find('input[name="name"]').val(),
        username: $(this).find('input[name="username"]').val(),
        email: $(this).find('input[name="email"]').val(),
        password: $(this).find('input[name="password"]').val(),
        password_confirmation: $(this).find('input[name="password_confirmation"]').val()
    };
    
    $.post('/api-demo/register', formData)
        .done(function(response) {
            displayResponse('register-response', response);
        })
        .fail(function(xhr) {
            displayResponse('register-response', {
                status: xhr.status,
                data: { error: 'Request failed: ' + xhr.statusText }
            });
        });
});

// Login form
$('#login-form').on('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        username: $(this).find('input[name="username"]').val(),
        password: $(this).find('input[name="password"]').val()
    };
    
    $.post('/api-demo/login', formData)
        .done(function(response) {
            displayResponse('login-response', response);
        })
        .fail(function(xhr) {
            displayResponse('login-response', {
                status: xhr.status,
                data: { error: 'Request failed: ' + xhr.statusText }
            });
        });
});

// Get user info
function getUserInfo() {
    if (!currentToken) {
        displayResponse('user-info-response', {
            status: 401,
            data: { error: 'No token available. Please login first.' }
        });
        return;
    }
    
    $.post('/api-demo/me', { token: currentToken })
        .done(function(response) {
            displayResponse('user-info-response', response);
        })
        .fail(function(xhr) {
            displayResponse('user-info-response', {
                status: xhr.status,
                data: { error: 'Request failed: ' + xhr.statusText }
            });
        });
}

// Access protected route
function accessProtected() {
    if (!currentToken) {
        displayResponse('protected-response', {
            status: 401,
            data: { error: 'No token available. Please login first.' }
        });
        return;
    }
    
    $.post('/api-demo/protected', { token: currentToken })
        .done(function(response) {
            displayResponse('protected-response', response);
        })
        .fail(function(xhr) {
            displayResponse('protected-response', {
                status: xhr.status,
                data: { error: 'Request failed: ' + xhr.statusText }
            });
        });
}

// Refresh token
function refreshToken() {
    if (!currentToken) {
        displayResponse('refresh-response', {
            status: 401,
            data: { error: 'No token available. Please login first.' }
        });
        return;
    }
    
    $.post('/api-demo/refresh', { token: currentToken })
        .done(function(response) {
            displayResponse('refresh-response', response);
        })
        .fail(function(xhr) {
            displayResponse('refresh-response', {
                status: xhr.status,
                data: { error: 'Request failed: ' + xhr.statusText }
            });
        });
}

// Logout
function logout() {
    if (!currentToken) {
        displayResponse('logout-response', {
            status: 401,
            data: { error: 'No token available. Please login first.' }
        });
        return;
    }
    
    $.post('/api-demo/logout', { token: currentToken })
        .done(function(response) {
            displayResponse('logout-response', response);
            if (response.data && response.data.success) {
                updateTokenDisplay(null);
            }
        })
        .fail(function(xhr) {
            displayResponse('logout-response', {
                status: xhr.status,
                data: { error: 'Request failed: ' + xhr.statusText }
            });
        });
}
</script>
@endsection