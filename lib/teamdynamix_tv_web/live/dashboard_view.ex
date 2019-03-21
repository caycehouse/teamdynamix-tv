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
              select: [:ticket_id, :title, :status, :days_old],
              order_by: t.days_old

    TeamdynamixTv.Repo.all(query)   
  end

  def get_resolutions(resp_group) do
    # Imports only from/2 of Ecto.Query.
    import Ecto.Query, only: [from: 2]

    # Query for our resolutions.
    query = from r in "resolutions",
              where: r.resp_group == type(^resp_group, :string),
              select: [:name, :closes],
              order_by: [desc: r.closes]

    TeamdynamixTv.Repo.all(query)
  end

  def mount(%{resp_group: resp_group}, socket) do
    if connected?(socket), do: Process.send_after(self(), :tick, 1000)
    {:ok, assign(socket, tickets: get_tickets(resp_group), resp_group: resp_group,
      resolutions: get_resolutions(resp_group))}
  end

  def handle_info(:tick, socket) do
    resp_group = socket.assigns.resp_group

    Process.send_after(self(), :tick, 1000)
    {:noreply, assign(socket, tickets: get_tickets(resp_group),
      resolutions: get_resolutions(resp_group))}
  end
end