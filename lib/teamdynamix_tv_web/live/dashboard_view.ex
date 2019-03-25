defmodule TeamdynamixTvWeb.DashboardView do
  use Phoenix.LiveView

  def render(assigns) do
    TeamdynamixTvWeb.PageView.render("index.html", assigns)
  end

  def get_tickets(resp_group) do
    # Imports only from/2 of Ecto.Query.
    import Ecto.Query, only: [from: 2]

    # Query for our tickets.
    query = from t in "tickets",
              where: t.resp_group == type(^resp_group, :string),
              where: t.status != "Closed",
              select: [:ticket_id, :title, :status, :days_old, :url, :status_color],
              order_by: t.days_old

    TeamdynamixTv.Repo.all(query)   
  end

  def get_old_resolutions(resp_group) do
    # Imports only from/2 of Ecto.Query.
    import Ecto.Query, only: [from: 2]

    # Query for our resolutions.
    query = from r in "resolutions",
              where: r.resp_group == type(^resp_group, :string),
              where: r.resolved_date < ago(1, "week"),
              select: [:name, :closes],
              order_by: [desc: r.closes]

    TeamdynamixTv.Repo.all(query)
  end

  def get_new_resolutions(resp_group) do
    # Imports only from/2 of Ecto.Query.
    import Ecto.Query, only: [from: 2]

    # Query for our resolutions.
    query = from r in "resolutions",
              where: r.resp_group == type(^resp_group, :string),
              where: r.resolved_date > ago(1, "week"),
              select: [:name, :closes],
              order_by: [desc: r.closes]

    TeamdynamixTv.Repo.all(query)
  end

  def get_printers() do
    # Imports only from/2 of Ecto.Query.
    import Ecto.Query, only: [from: 2]

    # Query for our printers.
    query = from p in "printers",
              where: p.status != "OK",
              select: [:name, :status, :status_color],
              order_by: [desc: p.name]

    TeamdynamixTv.Repo.all(query) 
  end

  def get_devices() do
    # Imports only from/2 of Ecto.Query.
    import Ecto.Query, only: [from: 2]

    # Query for our devices.
    query = from d in "devices",
              where: d.status != "OK",
              select: [:name, :status],
              order_by: [desc: d.name]

    TeamdynamixTv.Repo.all(query) 
  end

  def get_summary() do
    # Imports only from/2 of Ecto.Query.
    import Ecto.Query, only: [from: 2]

    # Query for our summary.
    query = from s in "papercutsummary",
              select: [:name, :status, :status_color],
              order_by: [desc: s.name]

    TeamdynamixTv.Repo.all(query) 
  end

  def mount(%{resp_group: resp_group}, socket) do
    if connected?(socket), do: Process.send_after(self(), :tick, 1000)
    {:ok, assign(socket, tickets: get_tickets(resp_group), printers: get_printers(),
      devices: get_devices(), resp_group: resp_group,
      summary: get_summary(),
      old_resolutions: get_old_resolutions(resp_group),
      new_resolutions: get_new_resolutions(resp_group))}
  end

  def handle_info(:tick, socket) do
    resp_group = socket.assigns.resp_group

    Process.send_after(self(), :tick, 1000)
    {:noreply, assign(socket, tickets: get_tickets(resp_group), printers: get_printers(),
      devices: get_devices(), summary: get_summary(),
      old_resolutions: get_old_resolutions(resp_group),
      new_resolutions: get_new_resolutions(resp_group))}
  end
end