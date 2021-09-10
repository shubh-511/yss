<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--<title>{{ config('app.name', 'Laravel') }}</title>-->
    <title>Soberlistic</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/mainCustom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/bower_components/Ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dist/css/AdminLTE.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/skins/_all-skins.min.css')}}">
    <link href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">

    @stack('calander')
    <style type="text/css">
      .image-area{
        width: 50%;
        height: 100px;
        position: absolute;
        top:40rem;
        bottom: 0;
        left: 0;
        right: 0;
          
        margin: auto;
      }
      .file-btn {margin-top:1%}
    </style>  
    @yield('header_styles')
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        
      <header class="main-header">
            <!-- Logo -->
            <a href="{{ url('login/dashboard') }}" class="logo">
              <!-- mini logo for sidebar mini 50x50 pixels -->

            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="175" height="43" viewBox="0 0 175 43">
            <image id="NoPath_-_Copy_40_" data-name="NoPath - Copy (40)" width="175" height="43" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAK8AAAArCAYAAAAOs9i7AAAACXBIWXMAAAsTAAALEwEAmpwYAAAJWmlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNi4wLWMwMDYgNzkuMTY0NzUzLCAyMDIxLzAyLzE1LTExOjUyOjEzICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0RXZ0PSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VFdmVudCMiIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczpwaG90b3Nob3A9Imh0dHA6Ly9ucy5hZG9iZS5jb20vcGhvdG9zaG9wLzEuMC8iIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIyLjMgKFdpbmRvd3MpIiB4bXA6Q3JlYXRlRGF0ZT0iMjAyMS0wMy0xN1QxNDoxNzo1MFoiIHhtcDpNZXRhZGF0YURhdGU9IjIwMjEtMDMtMTdUMTQ6MTg6NDJaIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyMS0wMy0xN1QxNDoxODo0MloiIGRjOmZvcm1hdD0iaW1hZ2UvcG5nIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjA1ZDM3NzViLWQyYjItZWU0Yi1hOTlmLTRjMmNiNDFiMmZiNyIgeG1wTU06RG9jdW1lbnRJRD0iYWRvYmU6ZG9jaWQ6cGhvdG9zaG9wOmUwMjgwMjhkLTZhZjctNWU0Ni1hZjE2LWYxMDQ3MWJkZDlkZCIgeG1wTU06T3JpZ2luYWxEb2N1bWVudElEPSJ4bXAuZGlkOjk2OTI0N2QxLTY5N2YtNDU0OS04MTI5LWM1ZTgyZTkxNjMyZCIgcGhvdG9zaG9wOkNvbG9yTW9kZT0iMyIgcGhvdG9zaG9wOklDQ1Byb2ZpbGU9InNSR0IgSUVDNjE5NjYtMi4xIj4gPHhtcE1NOkhpc3Rvcnk+IDxyZGY6U2VxPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0iY3JlYXRlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDo5NjkyNDdkMS02OTdmLTQ1NDktODEyOS1jNWU4MmU5MTYzMmQiIHN0RXZ0OndoZW49IjIwMjEtMDMtMTdUMTQ6MTc6NTBaIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgMjIuMyAoV2luZG93cykiLz4gPHJkZjpsaSBzdEV2dDphY3Rpb249InNhdmVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOmJlZjI2YmEyLTc2ZjYtNDg0MS05MjA5LWVhMjY4NDAwMDRlZCIgc3RFdnQ6d2hlbj0iMjAyMS0wMy0xN1QxNDoxODowM1oiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCAyMi4zIChXaW5kb3dzKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0iY29udmVydGVkIiBzdEV2dDpwYXJhbWV0ZXJzPSJmcm9tIGFwcGxpY2F0aW9uL3ZuZC5hZG9iZS5waG90b3Nob3AgdG8gaW1hZ2UvcG5nIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJkZXJpdmVkIiBzdEV2dDpwYXJhbWV0ZXJzPSJjb252ZXJ0ZWQgZnJvbSBhcHBsaWNhdGlvbi92bmQuYWRvYmUucGhvdG9zaG9wIHRvIGltYWdlL3BuZyIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6ZmZkYTY5ZDQtOTk5Zi1lZTQ5LWFhMzktMzZhOTYxYThmZWQ1IiBzdEV2dDp3aGVuPSIyMDIxLTAzLTE3VDE0OjE4OjAzWiIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIDIyLjMgKFdpbmRvd3MpIiBzdEV2dDpjaGFuZ2VkPSIvIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDowNWQzNzc1Yi1kMmIyLWVlNGItYTk5Zi00YzJjYjQxYjJmYjciIHN0RXZ0OndoZW49IjIwMjEtMDMtMTdUMTQ6MTg6NDJaIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgMjIuMyAoV2luZG93cykiIHN0RXZ0OmNoYW5nZWQ9Ii8iLz4gPC9yZGY6U2VxPiA8L3htcE1NOkhpc3Rvcnk+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOmJlZjI2YmEyLTc2ZjYtNDg0MS05MjA5LWVhMjY4NDAwMDRlZCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo5NjkyNDdkMS02OTdmLTQ1NDktODEyOS1jNWU4MmU5MTYzMmQiIHN0UmVmOm9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo5NjkyNDdkMS02OTdmLTQ1NDktODEyOS1jNWU4MmU5MTYzMmQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6CxHQuAAASnklEQVR4nO2deZxVxZXHv+f2o+kGhMaABHA3GoMOsio2gxqMK4kB0QlqHJcZo4lRidFoJrigMyii48clatQkJppoTKJhcYxZFIwgW3DDuCuLgiBtN2sD3e/V/HGq7q1b9zY0y3w+0/h+n8+l761br24tv6o6dc6pQvbqNwYERP9BH0Rv7b0+B+/FxRBM9vlaQZYh8iAeJIqQqMIPAaEPyPmIXBF/Q0RTEgGJ9BGbJ5EbQZ4DnnX51ngAEVEUISIYhDh4CzDGUFndibUrlrBmxSIK7auD7Pk3NjURL4p4z5LUJWBc/iBVR94vMa7ewjQBoijIrZbJkPMbW2cm9Uw6bzlpJu0KYBApIBJBnBJBmYTIu3d1bGw7SKrclhtxNvx2kUx9ufxq++Glp+8j+9egZSiw83EeMN7evyPCDOOymmo8ANMV5Glgb+Bz9rcJSiWIXCMAyPXAOIEfAIcg8q4gRBJBJBizNaqWsSsh7No7inOAn3nP04FhLcTtCSxDiQtwLnB/Kkaa7OOA6+x9JTAP2B/AYDDGkBotytjlsTPJew7wUE7488AwREKCbQT+GsS9ALg3J41xwI1BWA0wH9h3u3JbRpvHziLvWeQT1+F5oBawBAagHvgq8Isg7kXAfd7zf5AlrkNXlMD7bFNuy9glsDPIewbwSBD2KrA0CJsJDAEwpgTEAvm5Ob+/EJVrzwf+K3i3AFjvPX8O+Duw13blvow2ix0l75nAr4OwD4DDgEHA5uDdi8AQQUUIbxQ+G3g4iDsR+GkQNgsYCBwbhDsC77mtBSij7WJHyPsvwK+CsMXAYHu/EjgcWBHEedHFMcZAQuB/BR7bwveeB4ba+zmoyOGjO0rg3q3LfhltHdtL3tOB3wRhi9BRsc5Tib4CfC38sVECDxSRWIdncUZOuqAiyNFB2FPAKUHYHiiBe229CGW0dWwPeU8HHg/ClqJiQh3gq7jaAdfnpFGBjp4Dc96NIUvgbi2kMxUYGYT1QBdxn8+Jj2o77OVEF1NWsbVFbCt5R5Il7jJUDKhzAUoICsBs4OQW0qqw7w/LeTcGeMJ7rkZ1vLfnxJ1MlsA90RF4jyTIWXSi5IoioqgCyViyymgL2JZWOxF4MghbRb5cC/A3YEAQ9gCwxnsuoCLEQTm/Hw38MQgbS77abDIqcngwvYC5ENWIRLFpOooKRBXtiCoqiQqVFCrbExXau9/kJF3G/1e0lrwnAE8HYZ+iosJHOfFfwKrFPPwQ+BZZTUE1Os1/MSedk4BngrBx5IsQj6H6Zh/72LRrcuKX0cbRGvJ+hewI2ICKCotz4k8n0Qo4jANutvfzgWOAJd773VBz7xdy0jsR+HMQdh2JqdjHr1GthY8D7Dd3y4lfRhvG1sg7nCxxGtAR9/2c+M+R1QpcQ9bQMIOsWswReP+cdI+3afu4Hu0UIR5GDR8+DrBpd8qJX0YbxZbI+2Wyvgdr0BH3vZz4z6Ejqo/xwH/mxD0buDQnvAYdJfMIPJwsgW9ExZEQvyD0UFOxZB7QMSd+a9CR1nvhdUAXpJ8FdGTnO3iFqAbah4EtfXQY8GwQtg4l7rs58f9Elrg3kC+bfhP4JVDVwre35K8wHDVW+JgAXJ0T9yHU0cfHwSiBq8PIJl9dti/wINpZ16H+GLPQDhOao/cH7gQWogvZj4G/oB01xHeBuai6cCHwD3vNRDUqfYL4T9q4c1Etin8tsOUCuNZLd759Pxuti+ODNM+wcWfQ8ppggE1rDnCkF34oatJfgtZLnU3nMlSt+VP77fn2G7PtNd9eC4DjUA649AdDyv+6BuXQAuAT1Og1E607IH8k+WeyBFHiGt7O8e5+xmbExw3ky6RnkTUDz0G1EL7jeleUZINIy8agYskLpOXqm4AiMCmI+yBaH76r5ZfsNw9HPduInbytz4XFASix/E7WCW3EI9HGcf4bI4HfkR5tq9HF6bFouU80xI3Tj8QSGaIW1aoMxZhZVmc+soW4DnsAb9p85aV7BHAOIndjzCU2zYO9uJlRzaIHWk8AvW33HoCW3UcNcJS9pgEj7G+3hINQnrn0Nb7m7TBUXO0e/KbWXlcCV4XkHYqquHw0ooV8E5zXfIw/ku3RE8gn7hiyDjiLUE3GalRf/D/eu+5oLx2IJYnbLYB2sJm2IA632Kzd6vJp8QA6w/ieav+EjgiDgU2YEpUdOlt9b1y6W0CqUNn+XFv+LugCdgjwnN0B0IdEhViPesG9hMrwZ6LiywnA7zFmtG0cpxOvQ2e5TehA0hd1Ce2GyASS2WwxOhNNRkdmv93Efg90dMLmdawtTA06A30FHbWmojNlg43biHb8PDR69w0273fZ51dQ7dEHqG/JyWiHfw8YZb9bBE5FHa02obPQGrQ9FpBeoDfazQo1aNs68e4aVFw0tgzjUceser8ShqAjmo+N+MRNm3KnoY3iYwLwI71Nxf0G8GgQdwlKzNX2+WnU3DvFi+MI3B9YZozx8zAUnYqO8OJPsoW8LfjWT9BR8cdemCPwoGLT5qbqzt1p37GGTesaKFR1AGP62XgfknToT1Cx6T6vfBPt37U2n7EGRlRsaAS+gzbiIFseh/VizBueifxt4Hvo1Js3K86w19aw3MAzyQYgedyoGNMDtZD+iW1Xam+yfw+1f99B6w+0Xt704r6oHxaA3YELQZqA3wZpHuzdu+xeAXS0g+TJklbRzgJ+D7wO2gMqUVnyxSDhxeiQ/rrmI67g3UmmBodmdGH2I82Fv/mLs8hqFl5BiftpnLYIRphqlMD1Xtw90AY/FNzUblz6tSS90uFWWwEVavYtuXq5ByWRP5r0BeaYUnGvqKJAlx776ehbKgL8wcY5Ch0lrkH9NPwFX1cSkekhMIuTTWTx/rXvE4snnBnUQ40RuQAdnf8d7VxDgOUY8z2yGIWOnpfZayxpWdShXU6YI19La42twa0TnrJ/T0PJdDWqzoy/KQaMRK4qnPgQAbsLpWT/WpIngE22ZU+0z88CT+f0sNfdTQH4OlmTL+hQ/xoEG+SUGCOCuLPRxrW5j+PvSVZUKKEEXeUHeuLIVHRqvMF73RPtcX2Aoo7AkUtrODoV+XrcSWjFzjKmZDteBDol16KLRof+iNzVvLlxZMfde7G5cR0Ny9+lUFn9Q3SqHo2OqP1t/FXAz8H8AKQTiby4UP8YW554LbwRFT36oNNrEgk6E259UiwC5pHZ88cwstuqpqBt6KNSEpLWGMzlIG67lVuIb+uGv4IYgxH5NjozHEci/4OKffdj9y9uPfFAAE2I3NP+fU3cItoYqMgqbwokK+JwIXUTSt5lBuMT+AFUjeZb0Pqho9o98ce04j9Gncpv8eJG6D63E9ERO86gHbFrgX8L8rIB7UxFcLNAvPx5lKz6679Rh3hL8phIo0l6tsNidJWORBFRRcH56WxGR5dj0EXi0bbc3YArRU3it3kZ6ZkkmWk65+W2IYiwjmSxaVCZ+nzgSESmY8wxAYHnorOeC6xAjUIhDieZYbx1IrPJDiZeriPbdrkShQtcja5zTkDXHsfYv71Q7dIGMJMwJZAKtkE6cSN3g01rz1TZE07FKAD1ogVagy4IHPqj0+UA0vLmB2hv8+XNTuiUVwDu9MjehI6CjSSCPugK3K34/cVCLSqs+9hkv6Mjm1u0aZM8RdbxZyJOdRaPuAaQPG+4D4FBGLOqUNWJtSuX0LDsXQqVqcX3dBKCdEJFoBHAtWBuA3nWludckPGOJsZWtsAlJKqop0ijQYyZELiF/gEl6dEGBoi2gcOTJJbK1sIlPg1jvpYzmhtgVbKtX0UzO7NtSa/9DInpvitatiOBS0AmJUm3GlV2iJmNzlIjDOwdwZKgfgrYQc/X806hZffCHpqVVGZqyapM7gAu1nyn4t5N1ijhdIgFYwxGF4YhcTeiBF8Iun/fS3UqWeJOIl/nO4p84g7EmFWF9tU0bVjNqsULMZTc2RJjUA3Fod5v1tkLkmluvP27L6r6cR26i8FcbFT3C7qgmRbkoZBDJt/3I5RP97Nhney1G9oxQhl3rhfHbWg9CF3fhIiAA206u9urmzGmgrRMKpZEF6EDke9MVU8yq9TjC/6th4s8EcBAlSgfTkYJW4GuOd5D7QSHhEaKyejU6sO5F3oENqDy5hCyBL4bVaGEuAu4PAgbiI40I8guGDcDR4B5NXZlTDCF7E6KW1ERBUj1+VGk3StBnYkGAiulooJSsZlPP3wDU2qmol17MCUBcyeJ3P8OqolZg2pOIOkkfyOxIg5DR45lqFhxtw3/mLRK0Yk5nzewHO1IS9EZ6ib77h+S1ImTlb9l46y11xqUOG4h2Nmlb1SdtN6oOLcEOAiRN0lG0w72bxWq5ahHVXd1qPZguC23g1t03osuGt9CDSsvoDOs63TWZB8T1y30OpBls9/p2llD0dsk/il7oiP6crQOp6BHJZwN1OZZ2J5AZT0fvVGSqtI4YUYzSuCXgvg/QVfP6WLoQiwk8Ah0RPIl8qJN99VYsDcGMQbRqTPcnXE7qrgO8XWyxF2BqqxWAkSFSjauq2fj+tVUtK/2Z4xvo2qaRlQfORQd6d4CLjTwM6+DXIPW2Wz73BNdyK20ddEHWOppbFahsuNq1Gm+N9pQVejIch/GHInEwuf7qCzoa0p8uHjLUUJ/5AIjQLS+VgD7CVxgc1Fnv19P/vxu0JG3wV5ufXIOuugrogafoWiHeBWdraYSnyJksOmvtnlKWYFseVz6uqlW6/9hdI3xF/udbvZai65x9gMekL36jYmP4omPNFJZ7XSQx4PjnpaADECkLjjeqVJE5gF9kyN+BETOE0m2xBuJnKHh+8CtTkWWfFNA+BSR40H+rtmxxz3p+98hMjo5DkkQ5A4RGesfEWWPCxohItOE5PcgK0RkICIfufK2q6xi49p66hYvtL/1+rPWQzXIIQhdQD4E3trKcU+9BfkisNYILwvSJGSOe6oEqRQoecc9iW2ojbEqKYrjVwORm7mD454iYKOINIO0NyoaNCGyUatIy2OgEpEq0UXhUiNSAKoEKbl2F1w9EYE0ikgJ6KAGSNlsYDPJcU+7GZE+InQSWGSQ99zxTEhqpmwnUCUiRSWrMSIVNtsmQqhW+6Y1lmSPe+puRA4BmiKRl4H17rinLZEXkDGIPBqcVbYYkQEi8qlHXkSkCpgjSF+PvIjwTexGTROTQwCuEpGbA/IWEQYj8lJSmTH5fivIaXFetGXuEuRSre7IJ+8IA9O08PHvV4AMFJGPkm868jZQt/i1lsibrpukgVsib5y/HTurLEXe5E2WvN69tyaIz/eKyeuVx/uuVzaPvHFdJsWSOI2tnVUWkFdLE6dr8Mibqi+X79aeVbY1b6DHyCrW90FFiK5BeGpx5eERg5yJROlMqmAeeoRFxNN/aiZ7jFCUEfmxGC4luwftJLILozp0QZjnOF9GG0VrXNkeJesZtS/qONMlCHdqrTeC8F+hZknAdcwIRG4mNifrK9TbaUbw/W+Qxr3Ad505wDv58DjS/hGgxB1E9hCUMto4WuuH+QgqqPtwOxQ6B+Eb0FEuJPDj6Mo/3Kyb58hzFMgjqOptTPDufuA7qrTGXw0eh9rsfbitSov0MZGJkysKpvMy2gq2xYn4l6j1x8cXUAKH+sj1KIE/CMKfwJqWTSm18LyBtDkY1Cci1A0/hKqvQhxNQFxBNgCDDWaRO53HmCLForuaKRabaW5uolhsTmTbMtoMttUD/uc4I0SCA1FjQ6gAdwSeF4RPQ13bwiH4OhIPrRBNqMrpvJx3tWRNpI3A4cbwvjGGkjGUSiVKJUva5ia9mppo2ryJYnN4KlUZbQHbs33jHrIE7ovqOENLTx2qLw3xZ9RbK8TVpP0gHFailp0QQ8i1ypnYGy6NPLEh1AKU0VawvXuP7kFt9j76oyOwT+C9yTr8OMwg/+Dpq7AO5R56kTXvDiFrldtEvsajjF0QO7Jx7m7Un9RHfxJCtUfl4S9tIY3nSe+GcLiStEO5oNqKyfb5QNTl0Yc1J6sbZxm7PnZ01+cdqFO0j4GoWW8O2T1Il5KdzmeSv+/qCrLHO52CqsKmk15dNaHEfaWV+S5jF8DO2LJ8J1l/hWPJnkF2Cuqc82XUycJHcuxpOvxytIP4OIn0KZBFlLgvb0umy2j72Fn77W9HR8qWcCrqwgjqsTSI9E4Kd+jeqeC2occ0HkviVhjCebaFjkFlfAawMw+LuI18Ao8ie0Cfc0n8JMhLfFRTcI7CZSTuhQ6OuPMp4zOJnX3SyW2kncFPI9nIGGIJOgK7zZZTyfoS+7gEt81IUUtWh1zGZwj/F/+J4ETUP3UZumkyQMqmuwS1uF1E1vyMc8f0cDGa57+iC0LAOsjHW5zKOtvPCv4XXEkBVDdGz+cAAAAASUVORK5CYII="/>
            </svg>

            </a>
            <nav class="navbar navbar-static-top">
              <!-- Sidebar toggle button-->
              <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </a>

              <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                  
                  <li class="dropdown user user-menu">
                    <a style="padding-bottom: 35px!important;" href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <img src="{{ asset('uploads/user-unnamed.png') }}" class="user-image" alt="User Image">
                      <span class="hidden-xs"></span>
                    </a>
                    <ul class="dropdown-menu">
                      <!-- User image -->
                      <li class="user-header">
                        <img src="{{ asset('uploads/user-unnamed.png') }}" class="img-circle" alt="User Image">
                        <p>
                          {{Auth::user()->name}}
                          <small></small>
                        </p>
                      </li>
                      <!-- Menu Footer-->
                      <li class="user-footer">
                        <div class="pull-left">
                          <a href="{{url('login/profile')}}" class="btn btn-default btn-flat">Profile</a>
                        </div>
                        <div class="pull-right">
                          <a class="btn btn-default btn-flat" href="{{url('logout')}}">{{ __('Logout') }}</a>
                          
                        </div>
                      </li>
                        </ul>
                  </li>
                </ul>
              </div>
            </nav>
        </header>

         
        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
              <!-- Sidebar user panel -->
              <div class="user-panel">
                <div class="pull-left image">
                  <img src="{{ asset('uploads/user-unnamed.png') }}" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                  <p>{{Auth::user()->name}}</p>

                  <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
              </div>
              <!-- sidebar menu: : style can be found in sidebar.less -->
              
               @if(Auth::user()->role_id==1)
              <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                <li class="">
                  <a href="{{url('login/dashboard')}}">
                    <i class="fa fa-th"></i> <span>Dashboard</span>

                  </a>
                </li>
                
                <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Coaches</a>
                    </li>
                      <li>
                    <a href="{{url('login/sub/admin')}}">Sub Admin</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="treeview">
                  <a href="">
                    <i class="fa fa-tasks"></i> <span>General Settings</span>
                  </a>
                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                    <li>
                    <a href="{{url('login/settings')}}">Settings</a>
                    </li>
                  </ul>
                  <!-- <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li> -->

                </li>
                <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
               <!--  <li class="">
                  <a href="{{url('login/tickets')}}">
                    <i class="fa fa-font-awesome"></i> <span>Tickets</span>
                  </a>
                </li> -->
                <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
               <!--  <li class="">
                  <a href="{{url('login/insurance')}}">
                    <i class="fa fa-hospital-o"></i> <span>Insurance</span>
                  </a>
                </li> -->
                <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-area-chart"></i> <span>Regions</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-exchange"></i> <span>Transaction</span>
                  </a>
                </li>
                   
               
              </ul>
               @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Region", $module_name) && in_array("Label", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
                 
              </ul>
               @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Region", $module_name)  && in_array("Label", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                 
              </ul>
              @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Region", $module_name)  && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Region", $module_name) && in_array("Label", $module_name)  && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Region", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                 
              </ul>
               @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Label", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                 
              </ul>
              @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
                 
              </ul>
               @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Region", $module_name) && in_array("Label", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
              
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                 
              </ul>

               @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Region", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
              
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
                 
              </ul>
               @elseif(in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Region", $module_name)  && in_array("Label", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
              
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                   <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
                 
              </ul>



               @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 
              </ul>
              @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Region", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                   <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                 
              </ul>
              @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Label", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                 
              </ul>
              @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Label", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Region", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
              </ul>
              @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("label", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
              </ul>
             
              @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Region", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
               </ul>
                 @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Transaction", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
               </ul>
                @elseif(in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Region", $module_name) && in_array("Label", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                   <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
               </ul>
                 @elseif(in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Region", $module_name) && in_array("Transaction", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                   <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
               </ul>

               @elseif(in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("label", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
               </ul>
             
               @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 
              </ul>

              @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Category", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 
              </ul>
               @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Region", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                 
              </ul>
               @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Label", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                 
              </ul>
               @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
                 
              </ul>
               @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 
              </ul>
               @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Region", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
              </ul>
              @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Label", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Region", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
              </ul>
              @elseif(in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Label", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                
              </ul>
               @elseif(in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
                
              </ul>

               @elseif(in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Region", $module_name) && in_array("Label", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                
              </ul>
               @elseif(in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Region", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
                
              </ul>
               @elseif(in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Region", $module_name) && in_array("Label", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                  <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
                
              </ul>
               @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 
              </ul>
               @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Notification", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 
              </ul>
              @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Category", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 
              </ul>

              @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Region", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                 
              </ul>

              @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Label", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                 
              </ul>

              @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
                 
              </ul>
               @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                  <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 
              </ul>
              @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Category", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                  <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
               <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                
                 
              </ul>
              @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Region", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                  <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
               <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                
                 
              </ul>
               @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Label", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                  <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
              <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
              </ul>
              @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Transaction", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                  <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
             <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 
              </ul>
             
             @elseif(in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Region", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                 
              </ul>
             
              @elseif(in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Region", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                 
              </ul>
               @elseif(in_array("Booking", $module_name) && in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Transaction", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
                 
              </ul>
               @elseif(in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Region", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                 
              </ul>
              @elseif(in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Label", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                 
              </ul>
             
                @elseif(in_array("Listing", $module_name) && in_array("Notification", $module_name) && in_array("Category", $module_name) && in_array("Transaction", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
               <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
              </ul>
             
               @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Booking", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 
              </ul>
                @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Listing", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 
              </ul>
              @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Notification", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

               <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Category", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
              </ul>
              @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Region", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                 <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Label", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
              </ul>
             @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name)  && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name)  && in_array("Listing", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
              </ul>
                 @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name)  && in_array("Notification", $module_name) )
                 <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                </ul>
                 @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name)  && in_array("Category", $module_name) )
                 <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
               <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
              </ul>
                @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name)  && in_array("Region", $module_name) )
                <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
               <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
              </ul>
                 @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name)  && in_array("Label", $module_name) )
                 <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
               </ul>
                 @elseif(in_array("General Settings", $module_name)  && in_array("Booking", $module_name)  && in_array("Transaction", $module_name) )
                 <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
              </ul>
                 @elseif( in_array("Booking", $module_name)  && in_array("Listing", $module_name)  && in_array("Notification", $module_name) )
              <ul class="sidebar-menu" data-widget="tree">
                <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
              </ul>
                  @elseif( in_array("Booking", $module_name)  && in_array("Listing", $module_name)  && in_array("Category", $module_name) )
              <ul class="sidebar-menu" data-widget="tree">
                <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
              </ul>
             @elseif( in_array("Booking", $module_name)  && in_array("Listing", $module_name)  && in_array("Region", $module_name) )
              <ul class="sidebar-menu" data-widget="tree">
                <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
               </ul>
             @elseif( in_array("Booking", $module_name)  && in_array("Listing", $module_name)  && in_array("Label", $module_name) )
              <ul class="sidebar-menu" data-widget="tree">
                <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
              </ul>
               
             @elseif( in_array("Booking", $module_name)  && in_array("Listing", $module_name)  && in_array("Transaction", $module_name) )
              <ul class="sidebar-menu" data-widget="tree">
                <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                   <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
              </ul>
                 @elseif( in_array("Listing", $module_name)  && in_array("Notification", $module_name)  && in_array("Category", $module_name) )
                 <ul class="sidebar-menu" data-widget="tree">
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
              </ul>
                 @elseif( in_array("Listing", $module_name)  && in_array("Notification", $module_name)  && in_array("Region", $module_name) )
                 <ul class="sidebar-menu" data-widget="tree">
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
              </ul>
                 @elseif( in_array("Listing", $module_name)  && in_array("Notification", $module_name)  && in_array("Label", $module_name) )
                 <ul class="sidebar-menu" data-widget="tree">
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
               <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
              </ul>

                 @elseif( in_array("Listing", $module_name)  && in_array("Notification", $module_name)  && in_array("Transaction", $module_name) )
                 <ul class="sidebar-menu" data-widget="tree">
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
               <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
              </ul>
                 @elseif( in_array("Notification", $module_name)  && in_array("Category", $module_name)  && in_array("Region", $module_name) )
                 <ul class="sidebar-menu" data-widget="tree">
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
               <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
              </ul>
                 @elseif( in_array("Notification", $module_name)  && in_array("Category", $module_name)  && in_array("Label", $module_name) )
                 <ul class="sidebar-menu" data-widget="tree">
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
               <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
              </ul>
                 @elseif( in_array("Notification", $module_name)  && in_array("Category", $module_name)  && in_array("Transaction", $module_name) )
                 <ul class="sidebar-menu" data-widget="tree">
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
               <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
              </ul>
                 @elseif( in_array("Category", $module_name)  && in_array("Region", $module_name)  && in_array("Label", $module_name) )
                 <ul class="sidebar-menu" data-widget="tree">
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
              </ul>
                @elseif( in_array("Category", $module_name)  && in_array("Region", $module_name)  && in_array("Transaction", $module_name) )
                <ul class="sidebar-menu" data-widget="tree">
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>   
                </ul>  
                 @elseif( in_array("Category", $module_name)  && in_array("Region", $module_name)  && in_array("Transaction", $module_name) )
                 <ul class="sidebar-menu" data-widget="tree">
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>   
              </ul>
                @elseif( in_array("User", $module_name)  && in_array("Region", $module_name)  && in_array("Category", $module_name) )
                 <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>  
                </ul>
                 @elseif( in_array("User", $module_name)  && in_array("Label", $module_name)  && in_array("Region", $module_name) )
                 <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>  
                </ul>              
                @elseif(in_array("User", $module_name) && in_array("General Settings", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                 
              </ul>
                @elseif(in_array("User", $module_name) && in_array("Booking", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 
              </ul>
               @elseif(in_array("User", $module_name) && in_array("Listing", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
                  <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 
              </ul>
               @elseif(in_array("User", $module_name) && in_array("Notification", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 
              </ul>
              @elseif(in_array("User", $module_name) && in_array("Category", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                 
              </ul>
              @elseif(in_array("User", $module_name) && in_array("Region", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                 <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                 
              </ul>
              @elseif(in_array("User", $module_name) && in_array("Label", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("User", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                 <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
              </ul>
                @elseif(in_array("General Settings", $module_name) && in_array("Booking", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
              
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
              </ul>
                @elseif(in_array("General Settings", $module_name) && in_array("Listing", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
              
                
                <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>
                   <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>

                </li>
              </ul>
             
              @elseif(in_array("General Settings", $module_name) && in_array("Notification", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
              
                
                <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>
                   <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>

                </li>
              </ul>
             
              @elseif(in_array("General Settings", $module_name) && in_array("Category", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
              
                
                <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>
                   <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                </li>
              </ul>
                @elseif(in_array("General Settings", $module_name) && in_array("Region", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
              
                
                <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>
                   <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                </li>
              </ul>
               @elseif(in_array("General Settings", $module_name) && in_array("Label", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
              
                
                <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>
                    <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                </li>
              </ul>
               @elseif(in_array("General Settings", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
              <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>
                     <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
                </li>
              </ul>
               @elseif(in_array("Booking", $module_name) && in_array("Listing", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("Booking", $module_name) && in_array("Notification", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("Booking", $module_name) && in_array("Category", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("Booking", $module_name) && in_array("Region", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                  <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("Booking", $module_name) && in_array("Label", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                  <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("Booking", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                  <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("Listing", $module_name) && in_array("Notification", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("Listing", $module_name) && in_array("Category", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
              </ul>
             
              @elseif(in_array("Listing", $module_name) && in_array("Region", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
              </ul>
                @elseif(in_array("Listing", $module_name) && in_array("Label", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
              </ul>
             
              @elseif(in_array("Listing", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("Notification", $module_name) && in_array("category", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("Notification", $module_name) && in_array("Region", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
              </ul>
                @elseif(in_array("Notification", $module_name) && in_array("Label", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
              </ul>
                @elseif(in_array("Notification", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
              </ul>
              @elseif(in_array("Region", $module_name) && in_array("category", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                   <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
              </ul>
                @elseif(in_array("Label", $module_name) && in_array("category", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                   <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
              </ul>
          
           @elseif(in_array("Category", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                   <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
                  <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("Region", $module_name) && in_array("Label", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                   <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                   <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("Region", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                   <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                   <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
              </ul>
               @elseif(in_array("Label", $module_name) && in_array("Transaction", $module_name) )
               <ul class="sidebar-menu" data-widget="tree">
                 <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                   <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
              </ul>
          
                 @elseif(in_array("User", $module_name))
               <ul class="sidebar-menu" data-widget="tree">
               <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
                   <li>
                    <a href="{{url('login/role')}}">Role</a>
                    </li>
                     <li>
                    <a href="{{url('login/module')}}">Module</a>
                    </li>
                    <li>
                    <a href="{{url('login/role/privilege')}}">Role Privilege</a>
                    </li>
                     <li>
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                  </ul>
                </li>
              </ul>
              @elseif(in_array("General Settings", $module_name))
               <li class="treeview">
                  <li>
                    <a href="{{url('login/settings')}}"><i class="fa fa-tasks"></i> <span>General Settings</span></a>
                  </li>

                </li>
                @elseif(in_array("Booking", $module_name))
                <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                 @elseif(in_array("Listing", $module_name))
                  <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                 @elseif(in_array("Notification", $module_name))
                 <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                 @elseif(in_array("Category", $module_name))
                 <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                @elseif(in_array("Region", $module_name))
                  <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                 @elseif(in_array("Label", $module_name))
                 <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>
                 @elseif(in_array("Transaction", $module_name))
                 <li class="">
                  <a href="{{url('login/transaction')}}">
                    <i class="fa fa-globe"></i> <span>Transaction</span>
                  </a>
                </li>
              @endif
            </section>
        <!-- /.sidebar -->
        </aside>
        <!-- Content Wrapper. Contains page content -->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
           

            <!-- Main content -->
            <section class="content">
                @include('admin.layouts.flash-message')
                @yield("content")
            </section>
            <!-- /.content -->
          </div>
          <!-- /.content-wrapper -->    

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 2.4.13
            </div>
            <strong>Copyright &copy; 2014-2019 <a href="#">Admin</a>.</strong> All rights reserved.
        </footer>
    </div>

     <!-- Scripts -->
    <script src="{{ asset('assets/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/iCheck/icheck.min.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('assets/bower_components/fastclick/lib/fastclick.js')}}"></script>
    <script src="{{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <!-- Admin App -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-confirmation/1.0.5/bootstrap-confirmation.min.js"></script>
    <script src="{{ asset('assets/bower_components/ckeditor/ckeditor.js')}}"></script>
    <script src="{{ asset('assets/dist/js/adminlte.min.js')}}"></script>
    <script src="{{ asset('assets/js/main.js')}}"></script>

    <script src="{{asset('js/jsRapCalendar.js')}}"></script>
    <script src="{{ asset('js/select2.js')}}"></script>
    <script>
       var url = window.location;
        $('ul.treeview-menu a').filter(function() {
            return this.href == url;
        }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');
    </script>
    @stack('select2')
    <script type="text/javascript">
      $( document ).ready(function(){
            $('.alert').fadeIn('slow', function(){
               $('.alert').delay(4000).fadeOut(); 
            });
        });
      function deleteUser(id)
      {
        if (confirm("Are you sure you want to delete?") == true) {
        $.ajax({
        url:"{{url('login/users/destroy')}}",
        type:'post',
        data:{'id':id,'_token':'{{ csrf_token() }}'},
        success: function(path){
        location.reload();
        }
        });
        } else {
        return false;
        }
      }
      function deleteCounsellor(id)
      {
        if (confirm("Are you sure you want to delete?") == true) {
        $.ajax({
        url:"{{url('login/counsellors/destroy')}}",
        type:'post',
        data:{'id':id,'_token':'{{ csrf_token() }}'},
        success: function(path){
        location.reload();
        }
        });
        } else {
        return false;
        }
      }

      </script>
    @yield('footer_scripts')
    
    

</body>
</html>